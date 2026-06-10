<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Concerns;

use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311OutputDataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311PowerModeBandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\DataObjects\MSA311RangeResolution;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311OpCode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311ReadRegister;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Resolution;

trait MSA311InternalAPI
{
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

    protected function s16le(int $lsb, int $msb): int
    {
        $value = (($msb & 0xFF) << 8) | ($lsb & 0xFF);

        return ($value & 0x8000) ? $value - 0x10000 : $value;
    }

    protected function write(MSA311OpCode $register_hex, array $command_data = []): ?int
    {
        return $this->carrier->write($register_hex->value, $command_data);
    }

    protected function read(MSA311ReadRegister $register_hex, int $length): array
    {
        return $this->carrier->read($register_hex->value, $length);
    }
}
