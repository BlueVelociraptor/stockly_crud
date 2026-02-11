<?php

namespace Tests\Feature;

use App\Models\Product;
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

    public function test_save_product_endpoint_should_detect_duplicated_name_in_database(): void
    {
        $product = Product::factory()->create();

        $payload = [
            "name" => $product->name,
            "price" => "150.00",
            "image" => UploadedFile::fake()->image("example.jpg"),
            "description" => "Example description",
        ];

        $response = $this->postJson("/api/product/save", $payload);

        $response->assertStatus(422);
        $this->assertDatabaseHas("products", ["name" => $product->name]);
    }

    public function test_get_all_products_endpoint_should_get_multiple_products_in_first_page(): void
    {
        Product::factory()->count(10)->create();

        $response = $this->get("/api/product/all?page=1");
        $productsList = $response["data"];

        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertCount(9, $productsList["products"]);
    }

    public function test_get_all_products_endpoint_should_get_multiple_products_in_next_page(): void
    {
        Product::factory()->count(10)->create();

        $response = $this->get("/api/product/all?page=2");
        $productsList = $response["data"];

        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertCount(1, $productsList["products"]);
    }

    public function test_get_product_by_id_endpoint_should_get_product_successfully(): void
    {
        $product = Product::factory()->create();

        $response = $this->get("/api/product/{$product->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);
        $this->assertDatabaseHas("products", [
            "id" => $product->id,
            "name" => $product->name,
        ]);
    }

    public function test_get_product_by_id_endpoint_should_catch_product_doesnt_exist_exception(): void
    {
        $response = $this->get("/api/product/1");

        $response->assertStatus(404);
        $response->assertJsonStructure(["success", "message"]);
        $this->assertDatabaseMissing("products", ["id" => 1]);
    }

    public function test_update_product_status_endpoint_should_get_product_with_updated_status(): void
    {
        $productSubject = Product::factory()->create();

        $response = $this->patch("/api/product/update-status/{$productSubject->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message", "data"]);

        $product = $response["data"];

        $this->assertDatabaseHas("products", [
            "id" => $product["id"],
            "name" => $product["name"],
            "status" => $product["status"],
        ]);

        $this->assertFalse($product["status"]);
    }

    public function test_update_product_status_endpoint_should_catch_product_doesnt_exist_exception(): void
    {
        $response = $this->patch("/api/product/update-status/1");

        $response->assertStatus(404);
        $response->assertJsonStructure(["success", "message"]);
        $this->assertDatabaseMissing("products", ["id" => 1]);
    }

    public function test_delete_product_endpoint_should_delete_the_product_in_database(): void
    {
        $product = Product::factory()->withProductImage()->create();

        $this->mockedCloudinaryService->shouldReceive("deleteImage")
            ->with($product->product_image->public_id)
            ->once()
            ->andReturnNull();

        $response = $this->delete("/api/product/delete/{$product->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure(["success", "message"]);

        $this->assertDatabaseMissing("products", [
            "id" => $product->id,
            "name" => $product->name,
        ]);

        $this->assertDatabaseMissing("product_image", [
            "id" => $product->product_image->id,
            "public_url" => $product->product_image->public_url,
            "public_id" => $product->product_image->public_id,
        ]);
    }

    public function test_delete_product_endpoint_should_catch_product_doesnt_exist_exception(): void
    {
        $response = $this->delete("/api/product/delete/1");

        $response->assertStatus(404);
        $response->assertJsonStructure(["success", "message"]);
        $this->assertDatabaseMissing("products", ["id" => 1]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
