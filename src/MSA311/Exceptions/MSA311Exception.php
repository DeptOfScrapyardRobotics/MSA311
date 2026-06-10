<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Exceptions;

use RuntimeException;

class MSA311Exception extends RuntimeException
{
    public static function invalidProperty(string $name): static
    {
        return new static("Invalid property $name");
    }

    public static function invalidChipId(int $chip_id): static
    {
        return new static(sprintf('Invalid MSA311 Chip ID — expected 0x13, got 0x%02X', $chip_id));
    }
}
