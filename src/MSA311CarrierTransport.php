<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use DeptOfScrapyardRobotics\Sensors\MSA311\Concerns\MSA311IO;
use GeneralPurposeIO\I2C\I2CSlave;

class MSA311CarrierTransport
{
    use MSA311IO;

    public readonly string $active_transport;

    /**
     * @throws MSA311Exception
     */
    public function __construct(
        protected ?I2CSlave $i2c = null,
    ) {
        $this->active_transport = $this->detectTransport();
    }

    /**
     * @param  array<int, int>  $data
     *
     * @throws MSA311Exception
     */
    public function write(int $register, array $data): int
    {
        $method = "{$this->active_transport}Write";

        return $this->{$method}($register, $data);
    }

    /**
     * @return array<int, int>
     *
     * @throws MSA311Exception
     */
    public function read(int $register, int $length): array
    {
        $method = "{$this->active_transport}Read";

        return $this->{$method}($register, $length);
    }

    /**
     * @throws MSA311Exception
     */
    protected function detectTransport(): string
    {
        if (! is_null($this->i2c)) {
            return 'i2c';
        }

        throw MSA311Exception::transportMissingProtocol();
    }

    public function close(): void
    {
        $this->i2c?->close();
    }
}
