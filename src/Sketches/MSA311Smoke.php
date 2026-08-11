<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Sketches;

use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311CatalogIc;
use Fabricate\Contracts\Sketches\Attributes\Sketch as SketchAttribute;
use Fabricate\Contracts\Sketches\SketchLoopResult;
use Fabricate\Sketches\Sketch;
use GeneralPurposeIO\Circuits\Types\SensorIC;
use GeneralPurposeIO\Contracts\Circuits\IntegratedCircuit;
use GeneralPurposeIO\Core\MagicAliases\Circuit;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Throwable;
use Waveforms\Contracts\Motion\MeasuresAcceleration;

#[SketchAttribute('msa311-smoke')]
class MSA311Smoke extends Sketch
{
    protected string $description = 'Smoke-test a provisioned MSA311 profile (Ctrl-C to end)';

    protected ?IntegratedCircuit $sensor = null;

    protected ?string $profileName = null;

    protected bool $stopRequested = false;

    protected bool $announced = false;

    protected int $lastSampleNs = 0;

    public function configureCommand(Command $command): void
    {
        $command->addOption(
            'profile',
            null,
            InputOption::VALUE_OPTIONAL,
            'circuits.php profile name (ic must be msa311)',
        );
    }

    public function boot(): void
    {
        if (extension_loaded('pcntl')) {
            pcntl_async_signals(true);
            $stop = function (): void {
                $this->stopRequested = true;
            };
            pcntl_signal(SIGINT, $stop);
            pcntl_signal(SIGTERM, $stop);
        }

        $profiles = $this->msa311Profiles();
        if ($profiles === []) {
            $this->error('No MSA311 profiles in config/circuits.php. Run: php workshop msa311:make-profile');

            return;
        }

        $requested = $this->option('profile');
        if (is_string($requested) && $requested !== '') {
            if (! isset($profiles[$requested])) {
                $this->error("Profile [{$requested}] is missing or not an MSA311 ic.");

                return;
            }
            $this->profileName = $requested;
        } elseif (count($profiles) === 1) {
            $this->profileName = array_key_first($profiles);
        } else {
            $this->profileName = $this->choice('Which MSA311 profile?', array_keys($profiles));
        }

        try {
            $this->sensor = Circuit::profile($this->profileName);
        } catch (Throwable $e) {
            $this->error($e->getMessage());
            $this->sensor = null;
        }
    }

    public function loop(): SketchLoopResult
    {
        if ($this->stopRequested) {
            $this->info('MSA311 smoke stopped.');

            return SketchLoopResult::STOP;
        }

        if (is_null($this->sensor) || is_null($this->profileName)) {
            return SketchLoopResult::STOP;
        }

        if (! $this->announced) {
            $ic = (string) (config("circuits.{$this->profileName}.ic") ?? 'msa311');
            $this->info("MSA311 smoke via Circuit::profile('{$this->profileName}') [{$ic}]");
            $this->line('  Sampling X/Y/Z g — Ctrl-C to end.');
            $this->announced = true;
        }

        $now = hrtime(true);
        if ($this->lastSampleNs !== 0 && ($now - $this->lastSampleNs) < 250_000_000) {
            usleep(10_000);

            return SketchLoopResult::CONTINUE;
        }

        try {
            if (! $this->sensor instanceof MeasuresAcceleration) {
                $this->error('Resolved IC does not implement MeasuresAcceleration.');

                return SketchLoopResult::STOP;
            }

            $this->line(sprintf(
                '  X=%+.3fg  Y=%+.3fg  Z=%+.3fg',
                $this->sensor->x(),
                $this->sensor->y(),
                $this->sensor->z(),
            ));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return SketchLoopResult::STOP;
        }

        $this->lastSampleNs = $now;

        return SketchLoopResult::CONTINUE;
    }

    public function shutdown(): void
    {
        if ($this->sensor instanceof SensorIC || $this->sensor instanceof IntegratedCircuit) {
            try {
                $this->sensor->close();
            } catch (Throwable) {
                //
            }
        }
        $this->sensor = null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function msa311Profiles(): array
    {
        $all = config('circuits', []);
        if (! is_array($all)) {
            return [];
        }

        $matched = [];
        foreach ($all as $name => $recipe) {
            if (! is_string($name) || ! is_array($recipe)) {
                continue;
            }
            $ic = $recipe['ic'] ?? null;
            if (is_string($ic) && ! is_null(MSA311CatalogIc::tryFrom($ic))) {
                $matched[$name] = $recipe;
            }
        }

        return $matched;
    }
}
