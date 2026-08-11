<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use GeneralPurposeIO\Contracts\Circuits\CircuitException;

class MSA311Exception extends CircuitException
{
    public static function transportMissingProtocol(): static
    {
        return new static('MSA311 devices require an I2C capable connection.');
    }

    public static function invalidChipId(int $chip_id, int $expected_id): static
    {
        return new static("Invalid MSA311 PARTID — expected {$expected_id}, got {$chip_id}");
    }
}
