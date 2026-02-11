<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Product_Image;
use App\Product\Data\SaveProductDTO;
use App\Product\Exceptions\ProductDoesntExistException;
use App\Product\Repositories\ProductRepository;
use App\Product\Services\ProductService;
use App\Shared\Services\DeleteProductImageService;
use App\Shared\Services\UploadProductImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private readonly ProductRepository | MockInterface $mockedProductRepository;
    private readonly UploadProductImageService | MockInterface $mockedUploadProductImageService;
    private readonly DeleteProductImageService | MockInterface $mockedDeleteProductImageService;
    private readonly ProductService $productService;

    protected function setUp(): void
    {
        $this->mockedProductRepository = Mockery::mock(ProductRepository::class);
        $this->mockedUploadProductImageService = Mockery::mock(UploadProductImageService::class);
        $this->mockedDeleteProductImageService = Mockery::mock(DeleteProductImageService::class);
        parent::setUp();
    }

    public function test_save_product_should_execute_successfully(): void
    {
        //Preparar
        Storage::fake("temp");

        $product = Product::factory()->make();
        $fakeImage = UploadedFile::fake()->image("example.jpg");
        $savedImageInFakeDisk = $fakeImage->store("products", "temp");
        $dto = new SaveProductDTO(
            name: $product->name,
            price: $product->price,
            image: $fakeImage,
            description: $product->description,
        );

        $this->mockedProductRepository->shouldReceive("createOne")
            ->with($dto)
            ->once()
            ->andReturn(new Product()->setRawAttributes([
                "id" => 1,
                "name" => $product->name,
            ]));

        $this->mockedUploadProductImageService->shouldReceive("dispatchJob")
            ->with($savedImageInFakeDisk, 1)
            ->once();

        //Actuar

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );
        $product = $this->productService->saveProduct($dto);
        //Asertar

        $this->assertInstanceOf(Product::class, $product);
    }

    public function test_get_all_products_should_return_pagination_correctly(): void
    {
        $paginator = new LengthAwarePaginator([
            new Product(),
            new Product(),
        ], total: 2, perPage: 9, currentPage: 1);

        $this->mockedProductRepository->shouldReceive("findAll")
            ->once()
            ->andReturn($paginator);

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );
        $productsPagination = $this->productService->getAllProducts();

        $this->assertCount(2, $productsPagination["products"]);
        $this->assertSame(9, $productsPagination["per_page"]);
        $this->assertSame(2, $productsPagination["total_pages"]);
        $this->assertSame(1, $productsPagination["current_page"]);
    }

    public function test_verify_product_exists_by_id_should_throw_product_doesnt_exist_exception(): void
    {
        $productId = 1;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturnNull();

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );

        $this->expectException(ProductDoesntExistException::class);
        $this->expectExceptionMessage("We couldn't find a product registered with the ID you provided!");

        $this->productService->verifyProductExistsById($productId);
    }

    public function test_get_product_by_id_should_return_a_product(): void
    {
        $productId = 1;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturn(new Product());

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );

        $product = $this->productService->getProductById($productId);

        $this->assertInstanceOf(Product::class, $product);
    }

    public function test_update_product_status_should_return_product_with_updated_status()
    {
        $product = Product::factory()
            ->make()
            ->setRawAttributes([
                "id" => 1
            ]);

        $updatedProduct = $product;
        $updatedProduct->status = false;
        $productId = $product->id;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturn($product);

        $this->mockedProductRepository->shouldReceive("updateStatus")
            ->with($product)
            ->once()
            ->andReturn($updatedProduct);

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );
        $productSubject = $this->productService->updateProductStatus($productId);

        $this->assertInstanceOf(Product::class, $productSubject);
        $this->assertFalse($productSubject->status);
    }

    public function test_delete_product_should_execute_successfully(): void
    {
        $product = Product::factory()->make()->setRawAttributes(["id" => 1]);
        $product_image = Product_Image::factory()->make();

        $product->setRelation("product_image", $product_image);

        $productId = $product->id;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturn($product);

        $this->mockedDeleteProductImageService->shouldReceive("uploadJob")
            ->once()
            ->andReturnNull();

        $this->mockedProductRepository->shouldReceive("deleteOne")
            ->with(Mockery::type(Product::class))
            ->once()
            ->andReturnTrue();

        $this->productService = new ProductService(
            $this->mockedProductRepository,
            $this->mockedUploadProductImageService,
            $this->mockedDeleteProductImageService,
        );

        $response = $this->productService->deleteProduct($productId);

        $this->assertTrue($response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
