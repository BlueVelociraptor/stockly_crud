<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Product_Image;
use App\Product\Repositories\ProductImageRepository;
use App\Product\Repositories\ProductRepository;
use App\Product\Services\ProductImageService;
use App\Shared\Cloudinary\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Mockery\MockInterface;
use Tests\Builds\CloudinaryDTOTestBuilder;
use Tests\TestCase;

class ProductImageServiceTest extends TestCase
{
    private readonly ProductRepository | MockInterface $mockedProductRepository;
    private readonly ProductImageRepository | MockInterface $mockedProductImageRepository;
    private readonly CloudinaryService | MockInterface $mockedCloudinaryService;
    private readonly ProductImageService $productImageService;

    protected function setUp(): void
    {
        $this->mockedProductRepository = Mockery::mock(ProductRepository::class);
        $this->mockedProductImageRepository = Mockery::mock(ProductImageRepository::class);
        $this->mockedCloudinaryService = Mockery::mock(CloudinaryService::class);
        parent::setUp();
    }

    public function test_save_product_image_should_save_successfully(): void
    {
        //Arrage
        Storage::fake("temp");
        $fakeImage = UploadedFile::fake()->image("example.jpg")->store("products", "temp");

        $product = Product::factory()->make();
        $cloudinaryDto = CloudinaryDTOTestBuilder::buildCloudinaryDTOTestSubject();
        $fakeImagePath = Storage::disk("temp")->path($fakeImage);
        $productId = 1;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturn($product);

        $this->mockedCloudinaryService->shouldReceive("uploadImage")
            ->with($fakeImagePath)
            ->once()
            ->andReturn($cloudinaryDto);

        $this->mockedProductImageRepository->shouldReceive("createOne")
            ->with($product, $cloudinaryDto)
            ->once()
            ->andReturn(new Product_Image());

        //Act

        $this->productImageService = new ProductImageService(
            $this->mockedProductRepository,
            $this->mockedProductImageRepository,
            $this->mockedCloudinaryService,
        );

        $this->productImageService->saveProductImage($fakeImage, $productId);

        //Assert

        $this->assertTrue(true, "saveProductImage method has been executed successfully!");
        Storage::assertMissing($fakeImagePath);
    }

    public function test_verify_product_exists_method_should_register_product_doesnt_exist_in_logs(): void
    {
        //Arrage
        Log::spy();

        $productId = 1;

        $this->mockedProductRepository->shouldReceive("findOneById")
            ->with($productId)
            ->once()
            ->andReturnNull();

        $this->productImageService = new ProductImageService(
            $this->mockedProductRepository,
            $this->mockedProductImageRepository,
            $this->mockedCloudinaryService
        );
        //Act

        $product = $this->productImageService->verifyProductExists($productId);

        //Assert

        Log::shouldHaveReceived("info")
            ->with("Image isn't uploading since product doesn't exist!");

        $this->assertNull($product);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
