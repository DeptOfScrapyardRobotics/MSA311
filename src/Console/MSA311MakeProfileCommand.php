<?php

namespace DeptOfScrapyardRobotics\Sensors\MSA311\Console;

use DeptOfScrapyardRobotics\Sensors\MSA311\Enums\MSA311CatalogIc;
use Fabricate\Console\Command;
use GeneralPurposeIO\Circuits\CircuitRegistry;
use GeneralPurposeIO\Circuits\Console\Concerns\ScaffoldsCircuitProfiles;
use GeneralPurposeIO\Circuits\Support\CircuitAttributeInspector;
use GeneralPurposeIO\Contracts\Circuits\CircuitException;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'msa311:make-profile')]
class MSA311MakeProfileCommand extends Command
{
    use ScaffoldsCircuitProfiles;

    protected ?string $signature = 'msa311:make-profile
                    {ic? : Catalog slug (msa311)}
                    {name? : Profile key to write into config/circuits.php}
                    {--protocol= : Protocol option label or factory name when non-interactive}';

    protected string $description = 'Scaffold a circuits.php profile for an MSA311 accelerometer';

    public function handle(CircuitRegistry $registry): int
    {
        $available = array_values(array_filter(
            MSA311CatalogIc::slugs(),
            static fn (string $ic): bool => isset($registry->listCircuits()[$ic]),
        ));

        if ($available === []) {
            $this->components->error('No MSA311 ICs are registered.');

            return self::FAILURE;
        }

        $ic = $this->argument('ic');
        if (is_null($ic) || $ic === '') {
            $ic = $this->choice('Which MSA311 IC?', $available);
        }

        $ic = (string) $ic;

        if (is_null(MSA311CatalogIc::tryFrom($ic))) {
            $this->components->error("IC [{$ic}] is not an MSA311 sensor.");

            return self::FAILURE;
        }

        try {
            $options = CircuitAttributeInspector::protocolOptions($registry->resolveClass($ic));
        } catch (CircuitException $e) {
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        $selected = $this->resolveProtocolOption($options);
        if (is_null($selected)) {
            return self::FAILURE;
        }

        $name = $this->argument('name');
        if (is_null($name) || $name === '') {
            $name = $this->ask('Profile name', $ic);
        }

        return $this->writePromptedProfile($ic, (string) $name, $selected);
    }
}
