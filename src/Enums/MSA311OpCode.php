<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Enums;

/**
 * Writable register addresses for the MSA311.
 */
enum MSA311OpCode: int
{
    // Writing bit 2 high issues a soft reset over I2C.
    case SOFT_RESET = 0x00;

    // Device identification — PART_ID returns 0x13
    case PART_ID = 0x01;

    // Acceleration output — 16-bit, left-justified, two's complement (little-endian pairs)
    case ACC_X_LSB = 0x02;
    case ACC_X_MSB = 0x03;
    case ACC_Y_LSB = 0x04;
    case ACC_Y_MSB = 0x05;
    case ACC_Z_LSB = 0x06;
    case ACC_Z_MSB = 0x07;

    // Status registers
    case MOTION_INTERRUPT = 0x09;
    case DATA_INTERRUPT = 0x0A;
    case TAP_ACTIVE_STATUS = 0x0B;
    case ORIENTATION_STATUS = 0x0C;

    // RANGE_RESOLUTION: bits [1:0] full-scale range, bits [3:2] resolution.
    case RANGE_RESOLUTION = 0x0F;

    // ODR: bits [3:0] output data rate, bits [7:5] per-axis disable flags.
    case ODR = 0x10;

    // POWER_MODE_BANDWIDTH: bits [7:6] power mode, bits [4:1] low-power bandwidth.
    case POWER_MODE_BANDWIDTH = 0x11;

    // SWAP_POLARITY: per-axis polarity inversion and X/Y swap.
    case SWAP_POLARITY = 0x12;

    // INT_SET_0: orientation / tap / per-axis active interrupt enables.
    case INT_SET_0 = 0x16;

    // INT_SET_1: new-data and freefall interrupt enables.
    case INT_SET_1 = 0x17;

    // INT_MAP_0: routes orientation / tap / active / freefall interrupts to INT1.
    case INT_MAP_0 = 0x19;

    // INT_MAP_1: routes the new-data interrupt to INT1.
    case INT_MAP_1 = 0x1A;

    // TAP_DURATION: tap quiet / shock timing and double-tap window.
    case TAP_DURATION = 0x2A;

    // TAP_THRESHOLD: bits [4:0] tap detection threshold.
    case TAP_THRESHOLD = 0x2B;
}
