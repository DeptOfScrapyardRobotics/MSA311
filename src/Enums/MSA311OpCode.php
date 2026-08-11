<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * MSA311 register map (Adafruit MSA3xx / PARTID-compatible).
 */
enum MSA311OpCode: int
{
    /** Part / WHO_AM_I identity register — expect 0x13. */
    case PARTID_REGISTER = 0x01;

    case OUT_X_L_REGISTER = 0x02;
    case OUT_X_H_REGISTER = 0x03;
    case OUT_Y_L_REGISTER = 0x04;
    case OUT_Y_H_REGISTER = 0x05;
    case OUT_Z_L_REGISTER = 0x06;
    case OUT_Z_H_REGISTER = 0x07;

    case MOTION_INT_REGISTER = 0x09;
    case DATA_INT_REGISTER = 0x0A;
    case CLICK_STATUS_REGISTER = 0x0B;

    case RES_RANGE_REGISTER = 0x0F;
    case ODR_REGISTER = 0x10;
    case POWER_MODE_REGISTER = 0x11;

    case INT_SET0_REGISTER = 0x16;
    case INT_SET1_REGISTER = 0x17;
    case INT_MAP0_REGISTER = 0x19;
    case INT_MAP1_REGISTER = 0x1A;

    case TAP_DUR_REGISTER = 0x2A;
    case TAP_TH_REGISTER = 0x2B;
}
