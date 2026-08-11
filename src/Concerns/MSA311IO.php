<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Concerns;

use DeptOfScrapyardRobotics\Sensors\MSA311\MSA311Exception;
use Fabricate\NutsAndBolts\Concerns\Splices16Bits;

trait MSA311IO
{
    use Splices16Bits;

    /**
     * @return array<int, int>
     *
     * @throws MSA311Exception
     */
    protected function i2cRead(int $register, int $length): array
    {
        if (! is_null($this->i2c)) {
            return $this->i2c->writeRead([$this->getLowByte($register)], $length);
        }

        throw MSA311Exception::transportMissingProtocol();
    }

    /**
     * @param  array<int, int>  $data
     *
     * @throws MSA311Exception
     */
    protected function i2cWrite(int $register, array $data = []): int
    {
        if (! is_null($this->i2c)) {
            $payload = [$this->getLowByte($register), ...$data];

            return $this->i2c->write($payload);
        }

        throw MSA311Exception::transportMissingProtocol();
    }
}
