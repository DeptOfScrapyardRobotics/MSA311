<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums;

/**
 * ADC output resolution — stored in RANGE_RESOLUTION (0x0F) bits [3:2].
 *
 * The MSA311 hardware is fixed at 12-bit; the resolution field is ignored on
 * this part but remains in the register map for MSA301 compatibility.
 */
enum MSA311Resolution: int
{
    case BIT14 = 0x00;
    case BIT12 = 0x01;
    case BIT10 = 0x02;
    case BIT8 = 0x03;

    /** Effective number of data bits. */
    public function bits(): int
    {
        return match ($this) {
            self::BIT14 => 14,
            self::BIT12 => 12,
            self::BIT10 => 10,
            self::BIT8 => 8,
        };
    }

    /** Right-shift applied to the 16-bit register word to extract the sample. */
    public function rightShift(): int
    {
        return 16 - $this->bits();
    }

    public function toBits(): string
    {
        return sprintf('%02b', $this->value);
    }
}
