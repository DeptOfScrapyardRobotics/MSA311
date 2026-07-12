<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Device power mode — stored in POWER_MODE_BANDWIDTH (0x11) bits [7:6].
 */
enum MSA311PowerMode: int
{
    case NORMAL = 0x00;
    case LOW_POWER = 0x01;
    case SUSPEND = 0x03;

    public function toBits(): string
    {
        return sprintf('%02b', $this->value);
    }
}
