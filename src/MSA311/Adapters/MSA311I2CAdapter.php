<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Adapters;

use Waveforms\Carriers\I2C\I2CDevice;

class MSA311I2CAdapter extends MSA311DataCarrier
{
    public function __construct(
        I2CDevice $carrier
    ) {
        parent::__construct($carrier);
    }

    /**
     * The MSA311 auto-increments its register pointer on multi-byte reads,
     * so the bare register address is written before clocking out $length bytes.
     */
    public function read(int $register_hex, int $length): array
    {
        /** @var I2CDevice $carrier */
        $carrier = &$this->carrier;

        return $carrier->readWrite([$register_hex & 0xFF], $length);
    }

    public function write(int $register_hex, array $command_data = []): int
    {
        $payload = [$register_hex & 0xFF, ...$command_data];

        return $this->carrier->write($payload);
    }
}
