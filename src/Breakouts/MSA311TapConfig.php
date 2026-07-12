<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts;

use BareMetal\Circuits\DataRegister;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311TapDuration;

/**
 * TAP_DURATION (0x2A)
 *
 * | bit 7     | bit 6     | bits [5:3] | bits [2:0]          |
 * | tap quiet | tap shock | reserved   | double-tap window   |
 */
readonly class MSA311TapConfig extends DataRegister
{
    public function __construct(
        public MSA311TapDuration $duration = MSA311TapDuration::MS250,
        public bool $tap_shock = false,
        public bool $tap_quiet = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->tap_quiet ? '1' : '0';
        $bit6 = $this->tap_shock ? '1' : '0';
        $bits543 = '000';
        $duration_bits = $this->duration->toBits();

        return "{$bit7}{$bit6}{$bits543}{$duration_bits}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        $duration = bindec("{$bits[2]}{$bits[1]}{$bits[0]}");

        return new static(
            MSA311TapDuration::from($duration),
            (bool) $bits[6],
            (bool) $bits[7],
        );
    }

    public static function none(): static
    {
        return new static(
            MSA311TapDuration::MS50,
            false,
            false,
        );
    }
}
