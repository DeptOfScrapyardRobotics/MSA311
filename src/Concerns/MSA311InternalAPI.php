<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Concerns;

use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311OpCode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Range;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311Resolution;
use DeptOfScrapyardRobotics\Sensors\MSA311\MSA311Exception;
use Fabricate\NutsAndBolts\Concerns\Splices16Bits;
use GeneralPurposeIO\Contracts\Circuits\BootScaffolding;

trait MSA311InternalAPI
{
    use BootScaffolding;
    use Splices16Bits;

    /** Adafruit MSA301/MSA311 PARTID. */
    protected int $hardwired_part_id = 0x13;

    protected function sendCommand(MSA311OpCode $register, array $command_data = []): int
    {
        return $this->transport->write($register->value, $command_data);
    }

    /**
     * @return array<int, int>
     */
    protected function readData(MSA311OpCode $register, int $length): array
    {
        return $this->transport->read($register->value, $length);
    }

    /**
     * @throws MSA311Exception
     */
    protected function _boot(): void
    {
        $this->confirmPartId();
        $this->enableAxes(true, true, true);
        $this->setPowerMode(MSA311PowerMode::NORMAL);
        $this->setDataRate(MSA311DataRate::RATE_500_HZ);
        $this->setBandwidth(MSA311Bandwidth::WIDTH_250_HZ);
        $this->setRange(MSA311Range::G4);
        $this->setResolution(MSA311Resolution::BIT_14);
    }

    /**
     * @throws MSA311Exception
     */
    protected function confirmPartId(): void
    {
        if ($this->part_id !== $this->hardwired_part_id) {
            throw MSA311Exception::invalidChipId($this->part_id, $this->hardwired_part_id);
        }
    }

    /**
     * Convert 14-bit left-aligned raw count to g using current range scale.
     */
    protected function calcAccelerationG(int $raw14): float
    {
        return $raw14 / $this->getRange()->lsbPerG();
    }

    /**
     * Unpack one axis from a 6-byte XYZ frame (Adafruit: int16 LE >> 2).
     *
     * @param  array<int, int>  $frame
     */
    protected function axisFromFrame(array $frame, int $offset): int
    {
        return $this->s16le($frame[$offset] ?? 0, $frame[$offset + 1] ?? 0) >> 2;
    }
}
