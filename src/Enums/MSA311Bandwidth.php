<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Digital filter bandwidth — POWERMODE bits [4:1].
 */
enum MSA311Bandwidth: int
{
    case WIDTH_1_95_HZ = 0b0000;
    case WIDTH_3_9_HZ = 0b0011;
    case WIDTH_7_81_HZ = 0b0100;
    case WIDTH_15_63_HZ = 0b0101;
    case WIDTH_31_25_HZ = 0b0110;
    case WIDTH_62_5_HZ = 0b0111;
    case WIDTH_125_HZ = 0b1000;
    case WIDTH_250_HZ = 0b1001;
    case WIDTH_500_HZ = 0b1010;
}
