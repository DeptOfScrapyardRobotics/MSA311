<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Concerns;

use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311InterruptMap0;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311InterruptSet0;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311InterruptSet1;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311MotionInterrupt;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311OutputDataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311PowerModeBandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311RangeResolution;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311TapConfig;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311OpCode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311ReadRegister;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Resolution;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311TapDuration;

trait MSA311API
{
    use MSA311InternalAPI;

    public function getDeviceId(): int
    {
        return $this->read(MSA311ReadRegister::PART_ID, 1)[0] ?? -1;
    }

    public function getRangeResolution(): MSA311RangeResolution
    {
        $byte = $this->read(MSA311ReadRegister::RANGE_RESOLUTION, 1)[0] ?? 0x00;

        return MSA311RangeResolution::fromByte($byte);
    }

    public function setRangeResolution(MSA311RangeResolution $reg): void
    {
        $this->write(MSA311OpCode::RANGE_RESOLUTION, [$reg->toByte()]);
    }

    public function getOutputDataRate(): MSA311OutputDataRate
    {
        $byte = $this->read(MSA311ReadRegister::ODR, 1)[0] ?? 0x00;

        return MSA311OutputDataRate::fromByte($byte);
    }

    public function setOutputDataRate(MSA311OutputDataRate $reg): void
    {
        $this->write(MSA311OpCode::ODR, [$reg->toByte()]);
    }

    public function getPowerModeBandwidth(): MSA311PowerModeBandwidth
    {
        $byte = $this->read(MSA311ReadRegister::POWER_MODE_BANDWIDTH, 1)[0] ?? 0x00;

        return MSA311PowerModeBandwidth::fromByte($byte);
    }

    public function setPowerModeBandwidth(MSA311PowerModeBandwidth $reg): void
    {
        $this->write(MSA311OpCode::POWER_MODE_BANDWIDTH, [$reg->toByte()]);
    }

    public function getMotionInterrupt(): MSA311MotionInterrupt
    {
        $byte = $this->read(MSA311ReadRegister::MOTION_INTERRUPT, 1)[0] ?? 0x00;

        return MSA311MotionInterrupt::fromByte($byte);
    }

    public function getInterruptSet0(): MSA311InterruptSet0
    {
        $byte = $this->read(MSA311ReadRegister::INT_SET_0, 1)[0] ?? 0x00;

        return MSA311InterruptSet0::fromByte($byte);
    }

    public function setInterruptSet0(MSA311InterruptSet0 $reg): void
    {
        $this->write(MSA311OpCode::INT_SET_0, [$reg->toByte()]);
    }

    public function getInterruptSet1(): MSA311InterruptSet1
    {
        $byte = $this->read(MSA311ReadRegister::INT_SET_1, 1)[0] ?? 0x00;

        return MSA311InterruptSet1::fromByte($byte);
    }

    public function setInterruptSet1(MSA311InterruptSet1 $reg): void
    {
        $this->write(MSA311OpCode::INT_SET_1, [$reg->toByte()]);
    }

    public function getInterruptMap0(): MSA311InterruptMap0
    {
        $byte = $this->read(MSA311ReadRegister::INT_MAP_0, 1)[0] ?? 0x00;

        return MSA311InterruptMap0::fromByte($byte);
    }

    public function setInterruptMap0(MSA311InterruptMap0 $reg): void
    {
        $this->write(MSA311OpCode::INT_MAP_0, [$reg->toByte()]);
    }

    public function getTapConfig(): MSA311TapConfig
    {
        $byte = $this->read(MSA311ReadRegister::TAP_DURATION, 1)[0] ?? 0x00;

        return MSA311TapConfig::fromByte($byte);
    }

    public function setTapConfig(MSA311TapConfig $reg): void
    {
        $this->write(MSA311OpCode::TAP_DURATION, [$reg->toByte()]);
    }

    public function getRange(): MSA311Range
    {
        return $this->getRangeResolution()->range;
    }

    public function setRange(MSA311Range $value): void
    {
        $register = $this->getRangeResolution();
        $this->setRangeResolution(new MSA311RangeResolution(
            $value,
            $register->resolution,
        ));
    }

    public function getResolution(): MSA311Resolution
    {
        return $this->getRangeResolution()->resolution;
    }

    public function setResolution(MSA311Resolution $value): void
    {
        $register = $this->getRangeResolution();
        $this->setRangeResolution(new MSA311RangeResolution(
            $register->range,
            $value,
        ));
    }

    public function getDataRate(): MSA311DataRate
    {
        return $this->getOutputDataRate()->data_rate;
    }

    public function setDataRate(MSA311DataRate $value): void
    {
        $register = $this->getOutputDataRate();
        $this->setOutputDataRate(new MSA311OutputDataRate(
            $value,
            $register->x_axis_enabled,
            $register->y_axis_enabled,
            $register->z_axis_enabled,
        ));
    }

    public function getXAxisEnabled(): bool
    {
        return $this->getOutputDataRate()->x_axis_enabled;
    }

    public function setXAxisEnabled(bool $value): void
    {
        $register = $this->getOutputDataRate();
        $this->setOutputDataRate(new MSA311OutputDataRate(
            $register->data_rate,
            $value,
            $register->y_axis_enabled,
            $register->z_axis_enabled,
        ));
    }

    public function getYAxisEnabled(): bool
    {
        return $this->getOutputDataRate()->y_axis_enabled;
    }

    public function setYAxisEnabled(bool $value): void
    {
        $register = $this->getOutputDataRate();
        $this->setOutputDataRate(new MSA311OutputDataRate(
            $register->data_rate,
            $register->x_axis_enabled,
            $value,
            $register->z_axis_enabled,
        ));
    }

    public function getZAxisEnabled(): bool
    {
        return $this->getOutputDataRate()->z_axis_enabled;
    }

    public function setZAxisEnabled(bool $value): void
    {
        $register = $this->getOutputDataRate();
        $this->setOutputDataRate(new MSA311OutputDataRate(
            $register->data_rate,
            $register->x_axis_enabled,
            $register->y_axis_enabled,
            $value,
        ));
    }

    public function getPowerMode(): MSA311PowerMode
    {
        return $this->getPowerModeBandwidth()->power_mode;
    }

    public function setPowerMode(MSA311PowerMode $value): void
    {
        $register = $this->getPowerModeBandwidth();
        $this->setPowerModeBandwidth(new MSA311PowerModeBandwidth(
            $value,
            $register->bandwidth,
        ));
    }

    public function getBandwidth(): MSA311Bandwidth
    {
        return $this->getPowerModeBandwidth()->bandwidth;
    }

    public function setBandwidth(MSA311Bandwidth $value): void
    {
        $register = $this->getPowerModeBandwidth();
        $this->setPowerModeBandwidth(new MSA311PowerModeBandwidth(
            $register->power_mode,
            $value,
        ));
    }

    public function getNewDataReady(): bool
    {
        $byte = $this->read(MSA311ReadRegister::DATA_INTERRUPT, 1)[0] ?? 0x00;

        return ($byte & 0x01) > 0;
    }

    public function getRawX(): int
    {
        $bytes = $this->read(MSA311ReadRegister::ACC_X_LSB, 2);

        return $this->extractRawSample($bytes[0], $bytes[1]);
    }

    public function getRawY(): int
    {
        $bytes = $this->read(MSA311ReadRegister::ACC_Y_LSB, 2);

        return $this->extractRawSample($bytes[0], $bytes[1]);
    }

    public function getRawZ(): int
    {
        $bytes = $this->read(MSA311ReadRegister::ACC_Z_LSB, 2);

        return $this->extractRawSample($bytes[0], $bytes[1]);
    }

    public function getAcceleration(): array
    {
        // Read each axis pair separately to avoid depending on sensor-specific
        // burst auto-increment behavior across all MSA3xx board variants.
        $raw_x = $this->getRawX();
        $raw_y = $this->getRawY();
        $raw_z = $this->getRawZ();

        $scale = $this->getRange()->scaleFactor();
        $standard_gravity = 9.806;

        return [
            ($raw_x / $scale) * $standard_gravity,
            ($raw_y / $scale) * $standard_gravity,
            ($raw_z / $scale) * $standard_gravity,
        ];
    }

    /**
     * Enables single- or double-tap detection, mirroring the Adafruit CircuitPython driver.
     *
     * @param  int  $tap_count  1 to detect single taps, 2 to detect double taps.
     * @param  int  $threshold  Detection threshold (0-31); higher is less sensitive.
     * @param  bool  $long_initial_window  True sets the shock window to 70ms, false to 50ms.
     * @param  bool  $long_quiet_window  True sets the quiet window to 30ms, false to 20ms.
     * @param  MSA311TapDuration  $double_tap_window  Window in which a second tap counts as a double tap.
     */
    public function enableTapDetection(
        int $tap_count = 1,
        int $threshold = 25,
        bool $long_initial_window = true,
        bool $long_quiet_window = true,
        MSA311TapDuration $double_tap_window = MSA311TapDuration::MS250,
    ): void {
        if ($tap_count !== 1 && $tap_count !== 2) {
            throw new \InvalidArgumentException('tap_count must be 1 (single) or 2 (double).');
        }

        $this->write(MSA311OpCode::TAP_THRESHOLD, [$threshold & 0x1F]);

        $this->setTapConfig(new MSA311TapConfig(
            $double_tap_window,
            ! $long_initial_window,
            $long_quiet_window,
        ));

        $interrupts = $this->getInterruptSet0();
        $this->setInterruptSet0(new MSA311InterruptSet0(
            $interrupts->orientation_int_enabled,
            $tap_count === 1,
            $tap_count === 2,
            $interrupts->active_z_int_enabled,
            $interrupts->active_y_int_enabled,
            $interrupts->active_x_int_enabled,
        ));

        $this->tap_count = $tap_count;
    }

    public function getTapped(): bool
    {
        if ($this->tap_count === 0) {
            return false;
        }

        $status = $this->getMotionInterrupt();

        if ($this->tap_count === 1) {
            return $status->single_tap_interrupt;
        }

        return $status->double_tap_interrupt;
    }
}
