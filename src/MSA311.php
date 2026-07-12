<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use BareMetal\Sensors\Sensor;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311InterruptMap0;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311OutputDataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311PowerModeBandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311RangeResolution;
use DeptOfScrapyardRobotics\Sensors\MSA311\Breakouts\MSA311TapConfig;
use DeptOfScrapyardRobotics\Sensors\MSA311\DataObjects\MSA311InterruptSet0;
use DeptOfScrapyardRobotics\Sensors\MSA311\DataObjects\MSA311InterruptSet1;
use DeptOfScrapyardRobotics\Sensors\MSA311\DataObjects\MSA311MotionInterrupt;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Resolution;
use GPIO\Contracts\I2C\I2CCompatibleTransport;
use BareMetal\Contracts\Circuits\BootSequence;
use GPIO\Contracts\Digital\DigitalInputPin as DigitalInputInterface;
use BareMetal\Contracts\Sensors\Accelerometry\AccelerationMeasurable;

/**
 * @property-read int $device_id
 * @property-read array $acceleration
 * @property-read float $x
 * @property-read float $y
 * @property-read float $z
 * @property-read bool $new_data_ready
 * @property-read bool $tapped
 * @property-read MSA311MotionInterrupt $motion_interrupt
 * @property MSA311Range $fsr
 * @property MSA311Resolution $resolution
 * @property MSA311DataRate $data_rate
 * @property bool $x_axis_enabled
 * @property bool $y_axis_enabled
 * @property bool $z_axis_enabled
 * @property MSA311PowerMode $power_mode
 * @property MSA311Bandwidth $bandwidth
 * @property MSA311RangeResolution $range_resolution
 * @property MSA311OutputDataRate $output_data_rate_register
 * @property MSA311PowerModeBandwidth $power_mode_bandwidth
 * @property MSA311InterruptSet0 $interrupt_set0
 * @property MSA311InterruptSet1 $interrupt_set1
 * @property MSA311InterruptMap0 $interrupt_map0
 * @property MSA311TapConfig $tap_config
 */
class MSA311 extends Sensor implements AccelerationMeasurable, BootSequence
{
    use MSA311API;
    public function __construct(
        protected I2CCompatibleTransport $i2c,
        protected ?DigitalInputInterface $int1 = null,
        private readonly MSA311DataRate $_rate = MSA311DataRate::HZ500,
        private readonly MSA311Range $_range = MSA311Range::G4,
        bool $boot_now = false,
    ) {
        if($boot_now) {
            $this->boot();
        }
    }

    /**
     * @throws MSA311Exception
     */
    public function __get(string $name): mixed
    {
        return match($name) {
            'device_id' => $this->getDeviceId(),
            'range_resolution' => $this->getRangeResolution(),
            'output_data_rate_register' => $this->getOutputDataRate(),
            'power_mode_bandwidth' => $this->getPowerModeBandwidth(),
            'x' => $this->x(),
            'y' => $this->y(),
            'z' => $this->z(),
            'fsr' => $this->getRange(),
            'resolution' => $this->getResolution(),
            'data_rate' => $this->getDataRate(),
            'x_axis_enabled' => $this->getXAxisEnabled(),
            'y_axis_enabled' => $this->getYAxisEnabled(),
            'z_axis_enabled' => $this->getZAxisEnabled(),
            'power_mode' => $this->getPowerMode(),
            'bandwidth' => $this->getBandwidth(),
            'new_data_ready' => $this->getNewDataReady(),

            'interrupt_set0' => $this->getInterruptSet0(),
            'interrupt_set1' => $this->getInterruptSet1(),
            'interrupt_map0' => $this->getInterruptMap0(),
            'tap_config' => $this->getTapConfig(),
            'tapped' => $this->getTapped(),
            'motion_interrupt' => $this->getMotionInterrupt(),
            default => throw MSA311Exception::invalidProperty($name, static::class)
        };

    }

    /**
     * @throws MSA311Exception
     */
    public function __set(string $name, mixed $value): void
    {
        match($name) {
            'range_resolution' => $this->setRangeResolution($value),
            'output_data_rate_register' => $this->setOutputDataRate($value),
            'power_mode_bandwidth' => $this->setPowerModeBandwidth($value),
            'fsr' => $this->setRange($value),
            'resolution' => $this->setResolution($value),
            'data_rate' => $this->setDataRate($value),
            'x_axis_enabled' => $this->setXAxisEnabled($value),
            'y_axis_enabled' => $this->setYAxisEnabled($value),
            'z_axis_enabled' => $this->setZAxisEnabled($value),
            'power_mode' => $this->setPowerMode($value),
            'bandwidth' => $this->setBandwidth($value),

            'interrupt_set0' => $this->setInterruptSet0($value),
            'interrupt_set1' => $this->setInterruptSet1($value),
            'interrupt_map0' => $this->setInterruptMap0($value),
            'tap_config' => $this->setTapConfig($value),
            default => throw MSA311Exception::invalidProperty($name, static::class)
        };
    }

    public function x(): float
    {
        return $this->calcADXL(
            $this->getRawX()
        );
    }

    public function y(): float
    {
        return $this->calcADXL(
            $this->getRawY()
        );
    }

    public function z(): float
    {
        return $this->calcADXL(
            $this->getRawZ()
        );
    }
}
