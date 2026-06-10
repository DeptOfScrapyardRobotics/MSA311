<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums;

/**
 * Readable register addresses for the MSA311.
 *
 * The data registers auto-increment on multi-byte reads, so the six
 * acceleration bytes can be fetched in a single transaction starting
 * at {@see self::ACC_X_LSB}.
 */
enum MSA311ReadRegister: int
{
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

    // Configuration registers
    case RANGE_RESOLUTION = 0x0F;
    case ODR = 0x10;
    case POWER_MODE_BANDWIDTH = 0x11;
    case SWAP_POLARITY = 0x12;

    // Interrupt configuration
    case INT_SET_0 = 0x16;
    case INT_SET_1 = 0x17;
    case INT_MAP_0 = 0x19;
    case INT_MAP_1 = 0x1A;

    // Tap engine
    case TAP_DURATION = 0x2A;
    case TAP_THRESHOLD = 0x2B;
}
