<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Output resolution — RESRANGE bits [3:2].
 */
enum MSA311Resolution: int
{
    case BIT_14 = 0b00;
    case BIT_12 = 0b01;
    case BIT_10 = 0b10;
    case BIT_8 = 0b11;
}
