<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311;

use DeptOfScrapyardRobotics\Sensors\MSA311\Console\MSA311MakeProfileCommand;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311CatalogIc;
use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311ConsoleCommand;
use DeptOfScrapyardRobotics\Sensors\MSA311\Sketches\MSA311Smoke;
use Fabricate\Contracts\Sketches\SketchRegistry;
use Fabricate\NutsAndBolts\ServiceProvider;
use GeneralPurposeIO\Core\MagicAliases\Circuit;

class MSA311ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(MSA311MakeProfileCommand::class);
        $this->commands([
            MSA311MakeProfileCommand::class,
        ]);
    }

    public function boot(): void
    {
        Circuit::addCircuit(MSA311CatalogIc::MSA311->value, MSA311::class);

        $maker = MSA311ConsoleCommand::MAKE_PROFILE->value;
        foreach (MSA311CatalogIc::cases() as $ic) {
            Circuit::registerProfileCommand($ic->value, $maker);
        }

        $this->registerSketch();
    }

    protected function registerSketch(): void
    {
        if (! $this->container->bound(SketchRegistry::class)) {
            return;
        }

        /** @var SketchRegistry $registry */
        $registry = $this->container->make(SketchRegistry::class);

        if (! $registry->has('msa311-smoke')) {
            $registry->registerConvention('msa311-smoke', MSA311Smoke::class);
        }
    }
}
