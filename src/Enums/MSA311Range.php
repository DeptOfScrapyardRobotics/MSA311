<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Full-scale range — RESRANGE bits [1:0].
 *
 * Adafruit scale: 14-bit left-aligned samples divided by lsbPerG() yield g.
 */
enum MSA311Range: int
{
    case G2 = 0b00;
    case G4 = 0b01;
    case G8 = 0b10;
    case G16 = 0b11;

    /**
     * Divisor for 14-bit signed counts → g (Adafruit MSA301/MSA311).
     */
    public function lsbPerG(): float
    {
        return match ($this) {
            self::G2 => 4096.0,
            self::G4 => 2048.0,
            self::G8 => 1024.0,
            self::G16 => 512.0,
        };
    }

    /**
     * Approximate mg per LSB at 14-bit resolution.
     */
    public function mgPerLsb(): float
    {
        return 1000.0 / $this->lsbPerG();
    }

    public function label(): string
    {
        return match ($this) {
            self::G2 => '±2g',
            self::G4 => '±4g',
            self::G8 => '±8g',
            self::G16 => '±16g',
        };
    }
}
