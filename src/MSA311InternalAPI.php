<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use BareMetal\Contracts\Circuits\BootScaffolding;
use BareMetal\Contracts\Sensors\Accelerometry\CelestialBody;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311OutputDataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311PowerModeBandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311RangeResolution;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311OpCode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Resolution;
use ScrapyardIO\NutsAndBolts\Concerns\Splices16Bits;

trait MSA311InternalAPI
{
    use BootScaffolding, Splices16Bits;

    protected int $hardwired_device_id = 0x13;

    protected function sendCommand(MSA311OpCode $register, array $command_data = []): int
    {
        return $this->i2c->write([$register->value, ...$command_data]);
    }

    protected function readData(MSA311OpCode $register, int $length): array|false
    {
        return $this->i2c->writeRead([$register->value], $length);
    }

    /**
     * @throws MSA311Exception
     */
    protected function _boot(): void
    {
        $this->resetDevice();
        $this->confirmDeviceId();

        $this->initializeOutputDataRate($this->_rate);
        $this->initializePowerModeBandwidth();
        $this->initializeRangeResolution($this->_range);
        usleep(10000);
    }

    /**
     * Issues the documented soft reset (bit 2 of SOFT_RESET) before any
     * other configuration. Without this, the ADC can sit in an undefined
     * post-power-on state where config writes/readbacks still succeed but
     * the sampling pipeline never actually starts (ACC_X/Y/Z stay frozen
     * at rail values and DATA_INTERRUPT's new-data bit never sets).
     */
    protected function resetDevice(): void
    {
        $this->sendCommand(MSA311OpCode::SOFT_RESET, [0x04]);
        usleep(5000);
    }

    /**
     * @return void
     * @throws MSA311Exception
     */
    protected function confirmDeviceId(): void
    {
        if ($this->device_id != $this->hardwired_device_id) {
            throw MSA311Exception::invalidChipId($this->device_id);
        }
    }

    protected function initializeRangeResolution(MSA311Range $range): void
    {
        $this->setRangeResolution(new MSA311RangeResolution(
            $range,
            // MSA311 is a fixed 12-bit part; force the compatibility field
            // to BIT12 during init so the silicon is in its documented mode.
            MSA311Resolution::BIT12,
        ));
    }

    protected function initializeOutputDataRate(MSA311DataRate $rate): void
    {
        $this->setOutputDataRate(new MSA311OutputDataRate(
            $rate,
            true,
            true,
            true,
        ));
    }

    protected function initializePowerModeBandwidth(): void
    {
        $this->setPowerModeBandwidth(new MSA311PowerModeBandwidth(
            MSA311PowerMode::NORMAL,
            MSA311Bandwidth::HZ250,
        ));
    }

    protected function extractRawSample(int $lsb, int $msb): int
    {
        return $this->s16le($lsb, $msb) >> MSA311Resolution::BIT12->rightShift();
    }

    /**
     * MSA311Range::scaleFactor() is counts-per-g (e.g. 1024 at +-2g), the
     * inverse of ADXL345Range::scale()'s g-per-count - so raw counts are
     * divided here, not multiplied, before converting to m/s^2.
     */
    protected function calcADXL(int $value): float
    {
        return ($value / $this->getRange()->scaleFactor()) * CelestialBody::TERRA->gravity();
    }
}
