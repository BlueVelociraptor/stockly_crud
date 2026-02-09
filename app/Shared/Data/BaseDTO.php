<?php

declare(strict_types=1);

namespace App\Shared\Data;

abstract class BaseDTO
{
    public function toArray(array $excludedProperties): array
    {
        $formattedData = [];

        foreach (get_object_vars($this) as $propertyKey => $propertyValue) {
            if (!array_find($excludedProperties, fn($value) => $value === $propertyKey)) {
                $formattedData[$propertyKey] = $propertyValue;
            }
        }

        return $formattedData;
    }
}
