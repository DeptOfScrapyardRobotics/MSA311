<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

enum MSA311I2CAddress: int
{
    /** Fixed I2C address for MSA311 (Adafruit breakout). */
    case DEFAULT = 0x62;
}
