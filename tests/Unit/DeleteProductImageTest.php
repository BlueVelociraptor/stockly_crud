<?php

namespace Tests\Unit;

use App\Jobs\DeleteProductImage;
use App\Shared\Cloudinary\Services\CloudinaryService;
use Illuminate\Support\Facades\Bus;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class DeleteProductImageTest extends TestCase
{
    private readonly CloudinaryService | MockInterface $mockedCloudinaryService;

    protected function setUp(): void
    {
        $this->mockedCloudinaryService = Mockery::mock(CloudinaryService::class);
        parent::setUp();
    }

    public function test_job_should_initialize_with_properties_successfully(): void
    {
        $publicId = "12345";
        $jobSubject = new DeleteProductImage(
            imagePublicId: $publicId
        );

        $this->assertSame($publicId, $jobSubject->imagePublicId);
    }

    public function test_queue_list_should_add_job_successfully(): void
    {
        Bus::fake();
        $publicId = "12345";

        DeleteProductImage::dispatch($publicId);

        Bus::assertDispatchedOnce(DeleteProductImage::class);
    }

    public function test_job_should_run_successfully(): void
    {
        $publicId = "12345";

        $this->mockedCloudinaryService->shouldReceive("deleteImage")
            ->with($publicId)
            ->once()
            ->andReturnNull();

        $jobSubject = new DeleteProductImage(
            imagePublicId: $publicId,
        );

        $jobSubject->handle($this->mockedCloudinaryService);

        $this->assertTrue(true, "Job has been executed correctly!");
    }
}
