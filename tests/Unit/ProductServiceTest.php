<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Product\Data\SaveProductDTO;
use App\Product\Repositories\ProductRepository;
use App\Product\Services\ProductService;
use App\Shared\Services\UploadProductImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    private readonly ProductRepository | MockInterface $mockedProductRepository;
    private readonly UploadProductImageService | MockInterface $mockedUploadProductImageService;
    private readonly ProductService $productService;

    protected function setUp(): void
    {
        $this->mockedProductRepository = Mockery::mock(ProductRepository::class);
        $this->mockedUploadProductImageService = Mockery::mock(UploadProductImageService::class);
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

        $this->productService = new ProductService($this->mockedProductRepository, $this->mockedUploadProductImageService);
        $product = $this->productService->saveProduct($dto);
        //Asertar

        $this->assertInstanceOf(Product::class, $product);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
