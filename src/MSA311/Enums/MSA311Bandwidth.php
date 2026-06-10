<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums;

/**
 * Low-power mode bandwidth — stored in POWER_MODE_BANDWIDTH (0x11) bits [4:1].
 *
 * The encoding is sparse: 1.95 Hz is 0b0000, then values are contiguous from
 * 3.9 Hz (0b0011) upward.
 */
enum MSA311Bandwidth: int
{
    case HZ1_95 = 0x00;
    case HZ3_9 = 0x03;
    case HZ7_81 = 0x04;
    case HZ15_63 = 0x05;
    case HZ31_25 = 0x06;
    case HZ62_5 = 0x07;
    case HZ125 = 0x08;
    case HZ250 = 0x09;
    case HZ500 = 0x0A;

    /** Nominal bandwidth in Hz. */
    public function hz(): float
    {
        return match ($this) {
            self::HZ1_95 => 1.95,
            self::HZ3_9 => 3.9,
            self::HZ7_81 => 7.81,
            self::HZ15_63 => 15.63,
            self::HZ31_25 => 31.25,
            self::HZ62_5 => 62.5,
            self::HZ125 => 125.0,
            self::HZ250 => 250.0,
            self::HZ500 => 500.0,
        };
    }

    public function toBits(): string
    {
        return sprintf('%04b', $this->value);
    }
}
