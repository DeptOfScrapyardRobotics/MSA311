<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use BareMetal\Contracts\Sensors\SensorException;

class MSA311Exception extends SensorException
{
    public static function invalidChipId(int $chip_id): static
    {
        return new static("Invalid MSA311 Chip ID — expected 0x13, got {$chip_id}");
    }
}
