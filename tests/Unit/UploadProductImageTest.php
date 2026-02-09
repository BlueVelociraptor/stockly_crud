<?php

namespace Tests\Unit;

use App\Jobs\UploadProductImage;
use App\Product\Services\ProductImageService;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UploadProductImageTest extends TestCase
{
    private readonly UploadProductImage $uploadProductJob;
    private readonly ProductImageService | MockInterface $mockedProductImageService;

    protected function setUp(): void
    {
        $this->mockedProductImageService = Mockery::mock(ProductImageService::class);
        parent::setUp();
    }

    public function test_upload_product_image_job_should_initialize_with_current_properties(): void
    {
        $imageSavedInDisk = "/temp/products/product.jpg";
        $productId = 1;

        $this->uploadProductJob = new UploadProductImage(
            imageSavedInDisk: $imageSavedInDisk,
            productId: $productId,
        );

        $this->assertSame($imageSavedInDisk, $this->uploadProductJob->imageSavedInDisk);
        $this->assertSame($productId, $this->uploadProductJob->productId);
    }

    public function test_upload_product_image_job_should_dispatch_successfully(): void
    {
        Bus::fake();

        $imageSavedInDisk = "/temp/products/product.jpg";
        $productId = 1;

        UploadProductImage::dispatch($imageSavedInDisk, $productId);

        Bus::assertDispatchedOnce(UploadProductImage::class);
    }

    public function test_upload_product_image_job_should_dispatch_with_service_successfully(): void
    {
        $imageSavedInDisk = "/temp/products/product.jpg";
        $productId = 1;

        $this->mockedProductImageService->shouldReceive("saveProductImage")
            ->with($imageSavedInDisk, $productId)
            ->once();

        $this->uploadProductJob = new UploadProductImage(
            imageSavedInDisk: $imageSavedInDisk,
            productId: $productId,
        );

        $this->uploadProductJob->handle($this->mockedProductImageService);

        $this->assertTrue(true, "Job has been executed correctly!");
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
