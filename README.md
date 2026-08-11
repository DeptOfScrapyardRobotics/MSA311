# dept-of-scrapyard-robotics/msa311

Drive the **MSA311** 3-axis I2C accelerometer from PHP (ScrapyardIO 0.7).

## Install

```bash
composer require dept-of-scrapyard-robotics/msa311:^0.7.0
```

Requires `scrapyard-io/gpio-framework` and `scrapyard-io/waveforms` (^0.7).

## Quick use

```php
use DeptOfScrapyardRobotics\Sensors\MSA311\MSA311;

$accel = MSA311::i2c('/dev/i2c-1'); // slave 0x62, boots by default
echo $accel->x(), ' ', $accel->y(), ' ', $accel->z(); // g
$accel->close();
```

## Circuit profiles

```bash
php workshop msa311:make-profile
```

```php
use GeneralPurposeIO\Core\MagicAliases\Circuit;

$accel = Circuit::profile('msa311_board');
$gX = $accel->x();
```

Smoke: `php workshop runner msa311-smoke --profile=msa311_board`

## Notes

- PARTID register `0x01` must read `0x13`.
- Default I2C address `0x62`.
- Implements `Waveforms\Contracts\Motion\MeasuresAcceleration`.
