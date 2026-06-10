<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects;

use BareMetal\DataObjects\DataRegister;

/**
 * INT_SET_0 (0x16) — motion interrupt enables.
 *
 * | bit 7    | bit 6  | bit 5      | bit 4      | bit 3    | bit 2     | bit 1     | bit 0     |
 * | reserved | orient | single tap | double tap | reserved | active Z  | active Y  | active X  |
 */
readonly class MSA311InterruptSet0 extends DataRegister
{
    public function __construct(
        public bool $orientation_int_enabled = false,
        public bool $single_tap_int_enabled = false,
        public bool $double_tap_int_enabled = false,
        public bool $active_z_int_enabled = false,
        public bool $active_y_int_enabled = false,
        public bool $active_x_int_enabled = false,
    ) {}

    public function toBits(): string
    {
        $bit7 = '0';
        $bit6 = $this->orientation_int_enabled ? '1' : '0';
        $bit5 = $this->single_tap_int_enabled ? '1' : '0';
        $bit4 = $this->double_tap_int_enabled ? '1' : '0';
        $bit3 = '0';
        $bit2 = $this->active_z_int_enabled ? '1' : '0';
        $bit1 = $this->active_y_int_enabled ? '1' : '0';
        $bit0 = $this->active_x_int_enabled ? '1' : '0';

        return "{$bit7}{$bit6}{$bit5}{$bit4}{$bit3}{$bit2}{$bit1}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        return new static(
            (bool) $bits[6],
            (bool) $bits[5],
            (bool) $bits[4],
            (bool) $bits[2],
            (bool) $bits[1],
            (bool) $bits[0],
        );
    }

    public static function none(): static
    {
        return new static(false, false, false, false, false, false);
    }
}
