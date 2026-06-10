<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311;

use BareMetal\IntegratedCircuit;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Adapters\MSA311DataCarrier;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Concerns\MSA311API;
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
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Resolution;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Exceptions\MSA311Exception;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Factory\MSA311Factory;
use Exception;
use RealityInterface\Sensors\Attributes\MeasuresAcceleration;
use RealityInterface\Sensors\Contracts\Applied\Accelerometry\GenericAccelerometer;
use RealityInterface\Sensors\Enums\SensorType;
use Waveforms\Carriers\GPIO\GPIOBus;
use Waveforms\Carriers\I2C\I2C;

/**
 * @property-read int $device_id
 * @property-read array $acceleration
 * @property-read int $raw_x
 * @property-read int $raw_y
 * @property-read int $raw_z
 * @property-read bool $new_data_ready
 * @property-read bool $tapped
 * @property-read MSA311MotionInterrupt $motion_interrupt
 * @property MSA311Range $range
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
#[MeasuresAcceleration(SensorType::ACCELEROMETER)]
class MSA311 extends IntegratedCircuit implements GenericAccelerometer
{
    use MSA311API;

    protected bool $booted = false;

    protected int $hardwired_device_id = 0x13;

    protected int $tap_count = 0;

    public function __construct(
        protected readonly MSA311DataCarrier $carrier,
        protected readonly ?GPIOBus $gpio,
        MSA311DataRate $rate,
        MSA311Range $range,
    ) {
        $this->boot($rate, $range);
    }

    /**
     * @throws MSA311Exception
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'device_id' => $this->getDeviceId(),
            'acceleration' => $this->getAcceleration(),
            'raw_x' => $this->getRawX(),
            'raw_y' => $this->getRawY(),
            'raw_z' => $this->getRawZ(),
            'new_data_ready' => $this->getNewDataReady(),
            'tapped' => $this->getTapped(),
            'motion_interrupt' => $this->getMotionInterrupt(),
            'range' => $this->getRange(),
            'resolution' => $this->getResolution(),
            'data_rate' => $this->getDataRate(),
            'x_axis_enabled' => $this->getXAxisEnabled(),
            'y_axis_enabled' => $this->getYAxisEnabled(),
            'z_axis_enabled' => $this->getZAxisEnabled(),
            'power_mode' => $this->getPowerMode(),
            'bandwidth' => $this->getBandwidth(),
            'range_resolution' => $this->getRangeResolution(),
            'output_data_rate_register' => $this->getOutputDataRate(),
            'power_mode_bandwidth' => $this->getPowerModeBandwidth(),
            'interrupt_set0' => $this->getInterruptSet0(),
            'interrupt_set1' => $this->getInterruptSet1(),
            'interrupt_map0' => $this->getInterruptMap0(),
            'tap_config' => $this->getTapConfig(),
            default => throw MSA311Exception::invalidProperty($name)
        };
    }

    /**
     * @throws MSA311Exception
     */
    public function __set(string $name, mixed $value): void
    {
        match ($name) {
            'range' => $this->setRange($value),
            'resolution' => $this->setResolution($value),
            'data_rate' => $this->setDataRate($value),
            'x_axis_enabled' => $this->setXAxisEnabled($value),
            'y_axis_enabled' => $this->setYAxisEnabled($value),
            'z_axis_enabled' => $this->setZAxisEnabled($value),
            'power_mode' => $this->setPowerMode($value),
            'bandwidth' => $this->setBandwidth($value),
            'range_resolution' => $this->setRangeResolution($value),
            'output_data_rate_register' => $this->setOutputDataRate($value),
            'power_mode_bandwidth' => $this->setPowerModeBandwidth($value),
            'interrupt_set0' => $this->setInterruptSet0($value),
            'interrupt_set1' => $this->setInterruptSet1($value),
            'interrupt_map0' => $this->setInterruptMap0($value),
            'tap_config' => $this->setTapConfig($value),
            default => throw MSA311Exception::invalidProperty($name)
        };
    }

    protected function boot(MSA311DataRate $rate, MSA311Range $range): void
    {
        if (! $this->booted) {
            if ($this->device_id != $this->hardwired_device_id) {
                throw MSA311Exception::invalidChipId($this->device_id);
            }

            $this->initializeOutputDataRate($rate);
            $this->initializePowerModeBandwidth();
            $this->initializeRangeResolution($range);
            usleep(10000);

            $this->booted = true;
        }
    }

    /**
     * @throws Exception
     */
    public static function connection(string $driver): MSA311Factory
    {
        return new MSA311Factory(
            I2C::connection($driver),
        );
    }
}
