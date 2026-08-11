---
type: Module
title: Circuits integration
description: Catalog registration, msa311:make-profile, profiles, and msa311-smoke sketch.
resource: src/MSA311ServiceProvider.php
tags: [circuits, catalog, profile, smoke, workshop]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-11T19:56:00Z" }
verified: { by: null, at: null }
status: draft
sources:
  - id: provider
    resource: src/MSA311ServiceProvider.php
    title: MSA311ServiceProvider
  - id: catalog
    resource: src/Enums/MSA311CatalogIc.php
    title: MSA311CatalogIc
  - id: console-enum
    resource: src/Enums/MSA311ConsoleCommand.php
    title: MSA311ConsoleCommand
  - id: make-profile
    resource: src/Console/MSA311MakeProfileCommand.php
    title: msa311:make-profile
  - id: smoke
    resource: src/Sketches/MSA311Smoke.php
    title: msa311-smoke
---

# Role

This package owns the MSA311 chip driver and registers it with gpio-framework Circuits. Registry / fluent / profile semantics live in `scrapyard-io/gpio-framework`.

# Catalog

On `boot()`:[^provider]

```php
Circuit::addCircuit(MSA311CatalogIc::MSA311->value, MSA311::class); // 'msa311'

$maker = MSA311ConsoleCommand::MAKE_PROFILE->value; // 'msa311:make-profile'
foreach (MSA311CatalogIc::cases() as $ic) {
    Circuit::registerProfileCommand($ic->value, $maker);
}
```

Slug enum: `MSA311CatalogIc::MSA311` → `msa311`.[^catalog][^console-enum]

# Profiles

```bash
workshop vendor:publish --tag=gpio-circuits-config
workshop circuit:make-profile          # picks any installed IC; MSA311 delegates here
workshop msa311:make-profile           # MSA311 only
```

`msa311:make-profile` uses `ScaffoldsCircuitProfiles` + `CircuitAttributeInspector` — prompts from `#[IntegratedCircuit]` / `#[Pinout]`, writes `config/circuits.php` with `boot_now => true`.[^make-profile]

```php
use GeneralPurposeIO\Core\MagicAliases\Circuit;

/** @var \DeptOfScrapyardRobotics\Sensors\MSA311\MSA311 $accel */
$accel = Circuit::profile('msa311_board'); // recipe ic => msa311

$x = $accel->x(); // g
$y = $accel->y();
$z = $accel->z();
```

Example `config/circuits.php` recipe shape:

```php
'msa311_board' => [
    'ic' => 'msa311',
    'protocol' => 'i2c',
    'driver' => 'posix',      // or mpsse, etc.
    'device' => '/dev/i2c-1',
    'slave' => 0x62,
    'boot_now' => true,
],
```

# Smoke sketch

Sketch slug: `msa311-smoke`, registered when `SketchRegistry` is bound.[^provider][^smoke]

```bash
php workshop runner msa311-smoke
php workshop runner msa311-smoke --profile=msa311_board
```

Provisions **only** via `Circuit::profile()` — prints `X/Y/Z` g (~250 ms cadence) until Ctrl-C.

# Related

* [MSA311 IC](msa311.md)
* [Package (0.7)](../orientation/package.md)

[^provider]: MSA311ServiceProvider
[^catalog]: MSA311CatalogIc
[^console-enum]: MSA311ConsoleCommand
[^make-profile]: msa311:make-profile
[^smoke]: msa311-smoke
