<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts;

use BareMetal\Circuits\DataRegister;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Resolution;

/**
 * RANGE_RESOLUTION (0x0F)
 *
 * | bits [7:4] | bits [3:2]  | bits [1:0] |
 * | reserved   | resolution  | full range |
 */
readonly class MSA311RangeResolution extends DataRegister
{
    public function __construct(
        public MSA311Range $range = MSA311Range::G4,
        public MSA311Resolution $resolution = MSA311Resolution::BIT14,
    ) {}

    public function toBits(): string
    {
        $reserved = '0000';
        $resolution_bits = $this->resolution->toBits();
        $range_bits = $this->range->toBits();

        return "{$reserved}{$resolution_bits}{$range_bits}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        $resolution = bindec("{$bits[3]}{$bits[2]}");
        $range = bindec("{$bits[1]}{$bits[0]}");

        return new static(
            MSA311Range::from($range),
            MSA311Resolution::from($resolution),
        );
    }

    public static function none(): static
    {
        return new static(
            MSA311Range::G2,
            MSA311Resolution::BIT14,
        );
    }
}
