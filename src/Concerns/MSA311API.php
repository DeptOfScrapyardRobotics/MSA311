<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Concerns;

use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311OpCode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Resolution;

trait MSA311API
{
    use MSA311InternalAPI;

    public function getPartId(): int
    {
        [$id] = $this->readData(MSA311OpCode::PARTID_REGISTER, 1);

        return $id;
    }

    /**
     * @return array{0: int, 1: int, 2: int} raw 14-bit X/Y/Z counts
     */
    public function getRawAcceleration(): array
    {
        $frame = $this->readData(MSA311OpCode::OUT_X_L_REGISTER, 6);

        return [
            $this->axisFromFrame($frame, 0),
            $this->axisFromFrame($frame, 2),
            $this->axisFromFrame($frame, 4),
        ];
    }

    public function getRawX(): int
    {
        return $this->getRawAcceleration()[0];
    }

    public function getRawY(): int
    {
        return $this->getRawAcceleration()[1];
    }

    public function getRawZ(): int
    {
        return $this->getRawAcceleration()[2];
    }

    public function getRange(): MSA311Range
    {
        $register = $this->readData(MSA311OpCode::RES_RANGE_REGISTER, 1)[0] ?? 0;

        return MSA311Range::from($register & 0x03);
    }

    public function getResolution(): MSA311Resolution
    {
        $register = $this->readData(MSA311OpCode::RES_RANGE_REGISTER, 1)[0] ?? 0;

        return MSA311Resolution::from(($register >> 2) & 0x03);
    }

    public function getDataRate(): MSA311DataRate
    {
        $register = $this->readData(MSA311OpCode::ODR_REGISTER, 1)[0] ?? 0;

        return MSA311DataRate::from($register & 0x0F);
    }

    public function getPowerMode(): MSA311PowerMode
    {
        $register = $this->readData(MSA311OpCode::POWER_MODE_REGISTER, 1)[0] ?? 0;

        return MSA311PowerMode::from(($register >> 6) & 0x03);
    }

    public function getBandwidth(): MSA311Bandwidth
    {
        $register = $this->readData(MSA311OpCode::POWER_MODE_REGISTER, 1)[0] ?? 0;

        return MSA311Bandwidth::from(($register >> 1) & 0x0F);
    }

    public function setRange(MSA311Range $range): void
    {
        $register = $this->readData(MSA311OpCode::RES_RANGE_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~0x03) | $range->value;
        $this->sendCommand(MSA311OpCode::RES_RANGE_REGISTER, [$register]);
    }

    public function setResolution(MSA311Resolution $resolution): void
    {
        $register = $this->readData(MSA311OpCode::RES_RANGE_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~0x0C) | (($resolution->value & 0x03) << 2);
        $this->sendCommand(MSA311OpCode::RES_RANGE_REGISTER, [$register]);
    }

    public function setDataRate(MSA311DataRate $rate): void
    {
        $register = $this->readData(MSA311OpCode::ODR_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~0x0F) | ($rate->value & 0x0F);
        $this->sendCommand(MSA311OpCode::ODR_REGISTER, [$register]);
    }

    /**
     * Enable/disable axes. Hardware bits are *disable* flags on ODR[7:5].
     */
    public function enableAxes(bool $x = true, bool $y = true, bool $z = true): void
    {
        $register = $this->readData(MSA311OpCode::ODR_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~(0x07 << 5))
            | (((int) ! $x) << 7)
            | (((int) ! $y) << 6)
            | (((int) ! $z) << 5);
        $this->sendCommand(MSA311OpCode::ODR_REGISTER, [$register]);
    }

    public function setPowerMode(MSA311PowerMode $mode): void
    {
        $register = $this->readData(MSA311OpCode::POWER_MODE_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~(0x03 << 6)) | (($mode->value & 0x03) << 6);
        $this->sendCommand(MSA311OpCode::POWER_MODE_REGISTER, [$register]);
    }

    public function setBandwidth(MSA311Bandwidth $bandwidth): void
    {
        $register = $this->readData(MSA311OpCode::POWER_MODE_REGISTER, 1)[0] ?? 0;
        $register = ($register & ~(0x0F << 1)) | (($bandwidth->value & 0x0F) << 1);
        $this->sendCommand(MSA311OpCode::POWER_MODE_REGISTER, [$register]);
    }
}
