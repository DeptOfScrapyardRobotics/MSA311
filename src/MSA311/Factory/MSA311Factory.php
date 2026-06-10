<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Factory;

use BareMetal\CircuitFactory;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Adapters\MSA311I2CAdapter;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use Exception;
use Waveforms\Carriers\GPIO\Factory\GPIOConnectionBuilder;
use Waveforms\Carriers\I2C\Factory\I2CConnectionBuilder;

class MSA311Factory extends CircuitFactory
{
    public string $consumer = 'msa311';

    public MSA311DataRate $rate = MSA311DataRate::HZ500;

    public MSA311Range $range = MSA311Range::G4;

    protected ?GPIOConnectionBuilder $gpio_connection = null;

    public ?I2CConnectionBuilder $connection = null;

    public function __construct(
        public I2CConnectionBuilder $i2c_connection,
    ) {}

    public function i2c(string|int $chip_device, int $slave_address): static
    {
        $this->connection = $this->i2c_connection->firstly($chip_device)
            ->slaveAddress($slave_address);

        return $this;
    }

    public function int1(int $pin): static
    {
        return $this;
    }

    public function consumer(string $consumer): static
    {
        $this->consumer = $consumer;

        return $this;
    }

    public function dataRate(MSA311DataRate $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function fullScaleRange(MSA311Range $range): static
    {
        $this->range = $range;

        return $this;
    }

    public function create(): MSA311
    {
        $carrier = $this->connection?->boot();
        if (is_null($carrier)) {
            throw new Exception('A connection was not registered.');
        }

        $carrier = new MSA311I2CAdapter($carrier);

        $gpio = $this->gpio_connection?->consumer($this->consumer)->boot();

        return new MSA311(
            $carrier, $gpio,
            $this->rate,
            $this->range,
        );
    }
}
