<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use DeptOfScrapyardRobotics\Sensors\MSA311\Concerns\MSA311API;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311I2CAddress;
use Exception;
use GeneralPurposeIO\Circuits\Types\SensorIC;
use GeneralPurposeIO\Contracts\Circuits\Attributes\IntegratedCircuit;
use GeneralPurposeIO\Contracts\Circuits\Attributes\Pinout;
use GeneralPurposeIO\Contracts\Circuits\BootSequence;
use GeneralPurposeIO\I2C\I2C;
use GeneralPurposeIO\I2C\I2CSlave;
use Waveforms\Contracts\Motion\MeasuresAcceleration;

/**
 * @property-read int $part_id
 * @property-read float $x
 * @property-read float $y
 * @property-read float $z
 */
#[IntegratedCircuit('I2C')]
#[Pinout(['I2C' => ['driver', 'device', 'slave']])]
class MSA311 extends SensorIC implements BootSequence, MeasuresAcceleration
{
    use MSA311API;

    /**
     * @throws Exception
     */
    public function __construct(
        protected readonly MSA311CarrierTransport $transport,
        bool $boot_now = false,
    ) {
        if ($boot_now) {
            $this->boot();
        }
    }

    /**
     * @throws MSA311Exception
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'part_id' => $this->getPartId(),
            'x' => $this->x(),
            'y' => $this->y(),
            'z' => $this->z(),
            default => throw MSA311Exception::invalidProperty($name, static::class),
        };
    }

    public function x(): float
    {
        return $this->calcAccelerationG($this->getRawX());
    }

    public function y(): float
    {
        return $this->calcAccelerationG($this->getRawY());
    }

    public function z(): float
    {
        return $this->calcAccelerationG($this->getRawZ());
    }

    public function close(): void
    {
        $this->transport->close();
    }

    /**
     * Standalone I2C factory (typical addr 0x62).
     *
     * @throws Exception
     */
    public static function i2c(
        string|int $device,
        ?string $adapter = null,
        int $slave = MSA311I2CAddress::DEFAULT->value,
        bool $boot_now = true,
    ): static {
        $i2c = I2C::adapter($adapter)
            ->device($device)
            ->bus()
            ->slave($slave);

        return static::fromI2CBus($i2c, $boot_now);
    }

    /**
     * @throws Exception
     */
    public static function fromI2CBus(
        I2CSlave $i2c,
        bool $boot_now = true,
    ): static {
        $transport = new MSA311CarrierTransport(i2c: $i2c);

        return new static($transport, $boot_now);
    }
}
