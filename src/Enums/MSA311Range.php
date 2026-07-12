<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Measurement full-scale range — stored in RANGE_RESOLUTION (0x0F) bits [1:0].
 *
 * The MSA311 exposes a fixed 12-bit data field inside the 16-bit register
 * word. The scale factor is the LSB-per-g divisor applied after shifting that
 * field into position (see {@see MSA311Resolution::rightShift()}).
 */
enum MSA311Range: int
{
    /** ±2 g */
    case G2 = 0x00;

    /** ±4 g */
    case G4 = 0x01;

    /** ±8 g */
    case G8 = 0x02;

    /** ±16 g */
    case G16 = 0x03;

    /**
     * Counts-per-g divisor for the 12-bit extracted sample word.
     */
    public function scaleFactor(): float
    {
        return match ($this) {
            self::G2 => 1024.0,
            self::G4 => 512.0,
            self::G8 => 256.0,
            self::G16 => 128.0,
        };
    }

    /** Full-scale magnitude in g. */
    public function g(): int
    {
        return match ($this) {
            self::G2 => 2,
            self::G4 => 4,
            self::G8 => 8,
            self::G16 => 16,
        };
    }

    /** Human-readable range label. */
    public function label(): string
    {
        return match ($this) {
            self::G2 => '±2g',
            self::G4 => '±4g',
            self::G8 => '±8g',
            self::G16 => '±16g',
        };
    }

    public function toBits(): string
    {
        return sprintf('%02b', $this->value);
    }
}
