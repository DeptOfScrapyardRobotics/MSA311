<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;

/**
 * ODR (0x10)
 *
 * | bit 7    | bit 6    | bit 5    | bit 4    | bits [3:0] |
 * | X disable| Y disable| Z disable| reserved | data rate  |
 *
 * The hardware bits are active-high *disable* flags; this object exposes them
 * as friendlier active-high *enabled* flags and inverts them on serialization.
 */
readonly class MSA311OutputDataRate extends DataRegister
{
    public function __construct(
        public MSA311DataRate $data_rate = MSA311DataRate::HZ500,
        public bool $x_axis_enabled = true,
        public bool $y_axis_enabled = true,
        public bool $z_axis_enabled = true,
    ) {}

    public function toBits(): string
    {
        $bit7 = $this->x_axis_enabled ? '0' : '1';
        $bit6 = $this->y_axis_enabled ? '0' : '1';
        $bit5 = $this->z_axis_enabled ? '0' : '1';
        $bit4 = '0';
        $bits3210 = $this->data_rate->toBits();

        return "{$bit7}{$bit6}{$bit5}{$bit4}{$bits3210}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        $data_rate = bindec("{$bits[3]}{$bits[2]}{$bits[1]}{$bits[0]}");

        return new static(
            MSA311DataRate::from($data_rate),
            ! $bits[7],
            ! $bits[6],
            ! $bits[5],
        );
    }

    public static function none(): static
    {
        return new static(
            MSA311DataRate::HZ1,
            false,
            false,
            false,
        );
    }
}
