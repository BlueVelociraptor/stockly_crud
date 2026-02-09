<?php

namespace Tests\Unit;

use App\Shared\Helpers\JsonResponseBuilder;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class JsonResponseBuilderTest extends TestCase
{
    public function test_should_create_json_successfully_response_with_correct_data(): void
    {
        $statusCode = 201;
        $message = "Example Message";
        $data = "Example Data";

        $response = JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: $statusCode,
            message: $message,
            data: $data,
        );

        $responseData = $response->getData(
            assoc: true
        );

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertArrayHasKey("success", $responseData);
        $this->assertArrayHasKey("message", $responseData);
        $this->assertArrayHasKey("data", $responseData);

        $this->assertTrue($responseData["success"]);
        $this->assertSame($message, $responseData["message"]);
        $this->assertSame($data, $responseData["data"]);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    public function test_should_create_json_successfully_response_without_providing_data(): void
    {
        $statusCode = 201;
        $message = "Example Message";
        $data = null;

        $response = JsonResponseBuilder::buildSuccessfullyResponse(
            statusCode: $statusCode,
            message: $message,
            data: $data,
        );

        $responseData = $response->getData(
            assoc: true
        );

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertArrayHasKey("success", $responseData);
        $this->assertArrayHasKey("message", $responseData);
        $this->assertArrayNotHasKey("data", $responseData);

        $this->assertTrue($responseData["success"]);
        $this->assertSame($message, $responseData["message"]);
        $this->assertNull($data);
        $this->assertEquals($statusCode, $response->getStatusCode());
    }
}
