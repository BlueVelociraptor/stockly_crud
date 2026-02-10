<?php

namespace Tests\Unit;

use Tests\Builds\ExceptionTestBuilder;
use Tests\TestCase;

class BaseExceptionTest extends TestCase
{
    public function test_base_exception_should_build_unprocessable_error_response_with_unprocessable_field(): void
    {
        $exceptionTestSubject = new ExceptionTestBuilder(
            message: "Example_Message",
            statusCode: 200,
            unprocessableField: "Example_Field",
        );

        $exceptionJsonResponse = $exceptionTestSubject->render();
        $jsonData = $exceptionJsonResponse->getData(assoc: true);

        $this->assertArrayHasKey("success", $jsonData);
        $this->assertArrayHasKey("message", $jsonData);
        $this->assertArrayHasKey("errors", $jsonData);

        $this->assertSame(200, $exceptionJsonResponse->getStatusCode());
    }

    public function test_base_exception_should_build_unprocessable_error_response_without_unprocessable_field(): void
    {
        $exceptionTestSubject = new ExceptionTestBuilder(
            message: "Example_Message",
            statusCode: 200,
            unprocessableField: null,
        );

        $exceptionJsonResponse = $exceptionTestSubject->render();
        $jsonData = $exceptionJsonResponse->getData(assoc: true);

        $this->assertArrayHasKey("success", $jsonData);
        $this->assertArrayHasKey("message", $jsonData);
        $this->assertArrayNotHasKey("errors", $jsonData);

        $this->assertSame(200, $exceptionJsonResponse->getStatusCode());
    }
}
