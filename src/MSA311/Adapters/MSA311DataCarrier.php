<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Adapters;

use Waveforms\Carriers\I2C\I2CDevice;

abstract class MSA311DataCarrier
{
    public function __construct(
        protected I2CDevice $carrier
    ) {}

    abstract public function read(int $register_hex, int $length): array;

    abstract public function write(int $register_hex, array $command_data = []): int;
}
