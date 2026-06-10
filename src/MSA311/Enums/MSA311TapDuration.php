<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums;

/**
 * Double-tap detection window — stored in TAP_DURATION (0x2A) bits [2:0].
 *
 * Defines how long after an initial tap a second tap must arrive to count as
 * a double tap.
 */
enum MSA311TapDuration: int
{
    case MS50 = 0x00;
    case MS100 = 0x01;
    case MS150 = 0x02;
    case MS200 = 0x03;
    case MS250 = 0x04;
    case MS375 = 0x05;
    case MS500 = 0x06;
    case MS700 = 0x07;

    /** Window length in milliseconds. */
    public function milliseconds(): int
    {
        return match ($this) {
            self::MS50 => 50,
            self::MS100 => 100,
            self::MS150 => 150,
            self::MS200 => 200,
            self::MS250 => 250,
            self::MS375 => 375,
            self::MS500 => 500,
            self::MS700 => 700,
        };
    }

    public function toBits(): string
    {
        return sprintf('%03b', $this->value);
    }
}
