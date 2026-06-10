<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * INT_SET_1 (0x17) — new-data and freefall interrupt enables.
 *
 * | bits [7:5] | bit 4    | bit 3    | bits [2:0] |
 * | reserved   | new data | freefall | reserved   |
 */
readonly class MSA311InterruptSet1 extends DataRegister
{
    public function __construct(
        public bool $new_data_int_enabled = false,
        public bool $freefall_int_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bits765 = '000';
        $bit4 = $this->new_data_int_enabled ? '1' : '0';
        $bit3 = $this->freefall_int_enabled ? '1' : '0';
        $bits210 = '000';

        return "{$bits765}{$bit4}{$bit3}{$bits210}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            (bool) $bits[4],
            (bool) $bits[3],
        );
    }

    public static function none(): static
    {
        return new static(false, false);
    }
}
