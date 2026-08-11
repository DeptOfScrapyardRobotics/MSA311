<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Power mode — POWERMODE bits [7:6].
 */
enum MSA311PowerMode: int
{
    case NORMAL = 0b00;
    case LOW_POWER = 0b01;
    case SUSPEND = 0b10;
}
