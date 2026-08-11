<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Output data rate — ODR bits [3:0].
 */
enum MSA311DataRate: int
{
    case RATE_1_HZ = 0b0000;
    case RATE_1_95_HZ = 0b0001;
    case RATE_3_9_HZ = 0b0010;
    case RATE_7_81_HZ = 0b0011;
    case RATE_15_63_HZ = 0b0100;
    case RATE_31_25_HZ = 0b0101;
    case RATE_62_5_HZ = 0b0110;
    case RATE_125_HZ = 0b0111;
    case RATE_250_HZ = 0b1000;
    case RATE_500_HZ = 0b1001;
    case RATE_1000_HZ = 0b1010;
}
