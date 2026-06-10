<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums;

/**
 * Output data rate (ODR) — stored in ODR (0x10) bits [3:0].
 */
enum MSA311DataRate: int
{
    case HZ1 = 0x00;
    case HZ1_95 = 0x01;
    case HZ3_9 = 0x02;
    case HZ7_81 = 0x03;
    case HZ15_63 = 0x04;
    case HZ31_25 = 0x05;
    case HZ62_5 = 0x06;
    case HZ125 = 0x07;
    case HZ250 = 0x08;
    case HZ500 = 0x09;
    case HZ1000 = 0x0A;

    /** Nominal output data rate in Hz. */
    public function hz(): float
    {
        return match ($this) {
            self::HZ1 => 1.0,
            self::HZ1_95 => 1.95,
            self::HZ3_9 => 3.9,
            self::HZ7_81 => 7.81,
            self::HZ15_63 => 15.63,
            self::HZ31_25 => 31.25,
            self::HZ62_5 => 62.5,
            self::HZ125 => 125.0,
            self::HZ250 => 250.0,
            self::HZ500 => 500.0,
            self::HZ1000 => 1000.0,
        };
    }

    public function toBits(): string
    {
        return sprintf('%04b', $this->value);
    }
}
