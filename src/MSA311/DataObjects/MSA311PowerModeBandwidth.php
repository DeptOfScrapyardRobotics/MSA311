<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects;

use BareMetal\DataObjects\DataRegister;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311PowerMode;

/**
 * POWER_MODE_BANDWIDTH (0x11)
 *
 * | bits [7:6]  | bit 5    | bits [4:1]          | bit 0    |
 * | power mode  | reserved | low-power bandwidth | reserved |
 */
readonly class MSA311PowerModeBandwidth extends DataRegister
{
    public function __construct(
        public MSA311PowerMode $power_mode = MSA311PowerMode::NORMAL,
        public MSA311Bandwidth $bandwidth = MSA311Bandwidth::HZ250,
    ) {}

    public function toBits(): string
    {
        $power_mode_bits = $this->power_mode->toBits();
        $bit5 = '0';
        $bandwidth_bits = $this->bandwidth->toBits();
        $bit0 = '0';

        return "{$power_mode_bits}{$bit5}{$bandwidth_bits}{$bit0}";
    }

    public static function fromByte(int $byte): static
    {
        $bits = byte2bits($byte);

        $power_mode = bindec("{$bits[7]}{$bits[6]}");
        $bandwidth = bindec("{$bits[4]}{$bits[3]}{$bits[2]}{$bits[1]}");

        return new static(
            MSA311PowerMode::from($power_mode),
            MSA311Bandwidth::from($bandwidth),
        );
    }

    public static function none(): static
    {
        return new static(
            MSA311PowerMode::NORMAL,
            MSA311Bandwidth::HZ1_95,
        );
    }
}
