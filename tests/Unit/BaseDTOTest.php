<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Builds\DTOTestBuilder;

class BaseDTOTest extends TestCase
{
    private readonly DTOTestBuilder $dtoTest;

    protected function setUp(): void
    {
        $this->dtoTest = new DTOTestBuilder(
            name: "Alina Tsvetkov",
            age: 20,
        );

        parent::setUp();
    }

    public function test_should_create_new_array_from_child_dto_class_properties(): void
    {
        $formattedData = $this->dtoTest->toArray(
            excludedProperties: []
        );

        $this->assertArrayHasKey("name", $formattedData);
        $this->assertArrayHasKey("age", $formattedData);
        $this->assertSame($this->dtoTest->name, $formattedData["name"]);
        $this->assertSame($this->dtoTest->age, $formattedData["age"]);
    }

    public function test_should_create_new_array_with_excluded_properties(): void
    {
        $formattedData = $this->dtoTest->toArray(
            excludedProperties: [$this->dtoTest->getAgeProperty()],
        );

        $this->assertArrayHasKey("name", $formattedData);
        $this->assertArrayNotHasKey("age", $formattedData);
        $this->assertSame($this->dtoTest->name, $formattedData["name"]);
    }
}
