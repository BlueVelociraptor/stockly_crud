<?php

namespace Tests\Feature;

use App\Shared\Cloudinary\Data\ImageUploadedToCloudinaryDTO;
use App\Shared\Cloudinary\Services\CloudinaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery;
use Mockery\MockInterface;
use Tests\Builds\CloudinaryDTOTestBuilder;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    private readonly CloudinaryService | MockInterface $mockedCloudinaryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockedCloudinaryService = Mockery::mock(CloudinaryService::class);
        $this->app->instance(CloudinaryService::class, $this->mockedCloudinaryService);
    }

    public function test_save_product_endpoint_should_get_successfully_response_and_save_new_product(): void
    {
        $payload = [
            "name" => "Example name",
            "price" => "125.00",
            "image" => UploadedFile::fake()->image("example.jpg"),
            "description" => "Example description",
        ];

        $cloudinaryResponse = CloudinaryDTOTestBuilder::buildCloudinaryDTOTestSubject();

        $this->mockedCloudinaryService->shouldReceive("uploadImage")
            ->once()
            ->andReturn(new ImageUploadedToCloudinaryDTO(
                $cloudinaryResponse->public_url,
                $cloudinaryResponse->public_id,
            ));

        $response = $this->postJson("/api/product/save", $payload);

        $response->assertStatus(201);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertDatabaseHas("products", [
            "name" => "Example name",
            "price" => "125.00",
        ]);

        $this->assertDatabaseHas("product_image", [
            "public_url" => $cloudinaryResponse->public_url,
            "public_id" => $cloudinaryResponse->public_id,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
