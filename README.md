Introduction
============

PHP Package for the MSA3xx-family of motion sensors.

Compatible I2C Interfaces
===============
The MSA3xx sensors communicate with your device over I2C, the InterIntegrated Circuit Protocol.

You can interface with sensors like MSA311 with this package the following ways:
* A Linux Single-Board Computer's exposed GPIO pins using the dedicated I2C SDA/SCL pins
* An MPSSE-enabled USB-to-Serial device such as an FT232H generally using D0 for SCL and D1 for SDA connected to nearly any Linux or MacOS USB port.

The MSA311 has a fixed I2C address of `0x62` (the MSA301 uses `0x26`). It does not expose an
SPI interface.

Dependencies
=============
This package makes use of modules within:
* [The ScrapyardIO Framework](https://github.com/ScrapyardIO/framework)

This package also requires one of the following extensions in order to interface with I2C
* [POSI Extension v^0.4.0 or newer](https://github.com/php-io-extensions/posi)
* [FTDI Extension v^0.4.0 or newer](https://github.com/php-io-extensions/ftdi)

In addition, an extension wrapper package is needed

For ext-posi
* [Microscrap POSIX Package v0.4.0 or newer](https://github.com/microscrap/posix)
* [Microscrap Native I2C Package v0.4.0 or newer](https://github.com/microscrap/i2c)

For ext-ftdi
* [Microscrap FTDI Package v0.4.0 or newer](https://github.com/microscrap/ftdi)
* [Microscrap MPSSE Package v0.4.0 or newer](https://github.com/microscrap/mpsse)

Installing from Composer
====================
Inside the root of your PHP Project, simply require the MSA3xx package from composer
```shell
composer require dept-of-scrapyard-robotics/msa3xx
```
Framework Configuration
====================

If you would like to use the ScrapyardIO Framework to bootstrap your sensor without
wasting lines configuring your sensor right in the script you can add your desired
configuration to scrapyard-io.php, such as in this example:

### I2C
```php

use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;

return [
    'boards' => [
        // For Native Configurations 
        'msa311-native' => [
            'class_name' => MSA311::class,
            'connection' => ['driver' => 'native'],
            'startup' => [
                'i2c' => [
                    'chip_device' => 1,
                    'slave_address' => MSA311I2CAddress::DEFAULT->value,
                ],
            ],
        ],
        // For USB Configurations
        'msa311-usb' => [
            'class_name' => MSA311::class,
            'connection' => ['driver' => 'usb'],
            'startup' => [
                'i2c' => [
                    'chip_device' => 'ft232h',
                    'slave_address' => MSA311I2CAddress::DEFAULT->value,
                ],
            ],
        ],        
    ]
];

```

Basic Usage
============

### Native (POSIX) I2C driver. (Single Board Computers)
```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;

$native_i2c_sensor = MSA311::connection('native')
    ->i2c(1, MSA311I2CAddress::DEFAULT->value)
    ->create()
    
[$x, $y, $z] = $native_i2c_sensor->acceleration;

```

### USB (MPSSE) driver using I2C. (Linux and MacOS)
```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;

$usb_i2c_sensor = MSA311::connection('usb')
    ->i2c('ft232h', MSA311I2CAddress::DEFAULT->value)
    ->create()
    
[$x, $y, $z] = $usb_i2c_sensor->acceleration;

```

## Alternative Usage

### Using Through the Sensor Library (as an Accelerometer)
```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;
use RealityInterface\Sensors\Applied\Accelerometry\Accelerometer;

$msa311 = MSA311::connection('native')
    ->i2c(1, MSA311I2CAddress::DEFAULT->value)
    ->create();
    
$sensor = Accelerometer::as($msa311);

$data = $sensor->getAcceleration();

```

### Using Through the Sensor Framework (with an autoloaded config) (as an Accelerometer)
```php

use RealityInterface\Sensors\Applied\Accelerometry\Accelerometer;

$sensor = Accelerometer::using('msa311');

$data = $sensor->getAcceleration();

```

## Advanced Usage

You can preset the sensor's output data rate and range while building the connection:

```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;

$sensor = MSA311::connection('native')
    ->i2c(1, MSA311I2CAddress::DEFAULT->value)
    ->dataRate(MSA311DataRate::HZ500)
    ->fullScaleRange(MSA311Range::G4)
    ->create();
```

You can also tune measurement behavior at runtime using the MSA311's configuration properties:

* `data_rate` controls the output data rate and power consumption.
* `range` controls the full-scale measurement range (±2 g to ±16 g).
* `power_mode` selects `NORMAL`, `LOW_POWER`, or `SUSPEND` operation.
* `bandwidth` controls the low-power-mode filter bandwidth.
* `x_axis_enabled`, `y_axis_enabled`, `z_axis_enabled` toggle individual axes.

```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\MSA311;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Bandwidth;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311DataRate;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311I2CAddress;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311PowerMode;
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311Range;

$sensor = MSA311::connection('native')
    ->i2c(1, MSA311I2CAddress::DEFAULT->value)
    ->create();

// Set output data rate to 250 Hz
$sensor->data_rate = MSA311DataRate::HZ250;

// Set full-scale range to ±8 g
$sensor->range = MSA311Range::G8;

// Keep the part in normal (full-power) mode
$sensor->power_mode = MSA311PowerMode::NORMAL;

// Tune the low-power bandwidth filter
$sensor->bandwidth = MSA311Bandwidth::HZ125;

// Disable the Z axis if you only care about the X/Y plane
$sensor->z_axis_enabled = false;
```

> The MSA311 is a fixed 12-bit part. The `resolution` field exists in the RANGE_RESOLUTION
> register for MSA301 compatibility, but changing it has no effect on the MSA311's data width.

### Tap Detection

Tap detection is configured with `enableTapDetection()` and polled with the `tapped` property:

```php
use DeptOfScrapyardRobotics\Sensors\MSA3xx\MSA311\Enums\MSA311TapDuration;

// Single-tap detection with a moderate threshold
$sensor->enableTapDetection(tap_count: 1, threshold: 25);

// Double-tap detection with a 250 ms double-tap window
$sensor->enableTapDetection(
    tap_count: 2,
    threshold: 25,
    double_tap_window: MSA311TapDuration::MS250,
);

// Poll for a tap event
if ($sensor->tapped) {
    echo "Tap detected!";
}
```

## Calibration

The MSA311 driver does not expose the per-axis hardware offset registers, so calibration is
performed in software by collecting samples at a known orientation and applying a correction
to subsequent reads.

### Zero-G Offset Calibration

Place the sensor flat and level, then capture a baseline at rest:

```php
$samples = 50;
$sum_x = $sum_y = $sum_z = 0.0;

for ($i = 0; $i < $samples; $i++) {
    [$x, $y, $z] = $sensor->acceleration;
    $sum_x += $x;
    $sum_y += $y;
    $sum_z += $z;
    usleep(10000);
}

$gravity = 9.806;
$offset_x = $sum_x / $samples;              // should be ~0 m/s²
$offset_y = $sum_y / $samples;              // should be ~0 m/s²
$offset_z = ($sum_z / $samples) - $gravity; // should be ~0 m/s² after removing 1g

// Apply offsets to subsequent reads
[$raw_x, $raw_y, $raw_z] = $sensor->acceleration;
$cal_x = $raw_x - $offset_x;
$cal_y = $raw_y - $offset_y;
$cal_z = $raw_z - $offset_z;
```

### Range Selection and Sensitivity

Choosing the right range trades sensitivity for headroom. The MSA311 outputs 12-bit samples:

| Range  | Sensitivity   | Resolution  |
|--------|---------------|-------------|
| ±2 g   | 1024 LSB/g    | ~0.98 mg/LSB |
| ±4 g   | 512 LSB/g     | ~1.95 mg/LSB |
| ±8 g   | 256 LSB/g     | ~3.91 mg/LSB |
| ±16 g  | 128 LSB/g     | ~7.81 mg/LSB |

For most motion-sensing applications, `G2` or `G4` gives the best resolution. Use `G8` or
`G16` when measuring strong impacts or vibration.

## Sensor API

The getters and setters in this API interface with the device directly (register reads/writes),
so you can use property access while still working against the sensor itself. Multi-setting
registers are broken out into `DataObjects` and fields that only accept specific values are
enum-backed.

Readable Properties (Getters)
-----------------------------

* `$sensor->device_id`
  Reads and returns the MSA311 part ID. Should return `0x13`.

* `$sensor->acceleration`
  Reads all three axes and returns a `[x, y, z]` array in m/s².

* `$sensor->raw_x`, `$sensor->raw_y`, `$sensor->raw_z`
  Returns the signed 12-bit raw output for a single axis (no scaling applied).

* `$sensor->new_data_ready`
  Reads the DATA_INTERRUPT register and returns `true` when a fresh sample is available.

* `$sensor->tapped`
  Returns `true` if a tap matching the configured `enableTapDetection()` mode was detected.

* `$sensor->motion_interrupt`
  Reads and returns the full `MSA311MotionInterrupt` data object (orientation, single/double
  tap, active and freefall status flags).

* `$sensor->range`
  Returns the current `MSA311Range` full-scale measurement range.
  Possible values: `G2`, `G4`, `G8`, `G16`.

* `$sensor->resolution`
  Returns the current `MSA311Resolution`. Fixed at 12-bit behavior on the MSA311.
  Possible values: `BIT14`, `BIT12`, `BIT10`, `BIT8`.

* `$sensor->data_rate`
  Returns the current `MSA311DataRate` output data rate.
  Possible values: `HZ1`, `HZ1_95`, `HZ3_9`, `HZ7_81`, `HZ15_63`, `HZ31_25`, `HZ62_5`,
  `HZ125`, `HZ250`, `HZ500`, `HZ1000`.

* `$sensor->x_axis_enabled`
  Returns `true` if the X axis is active.

* `$sensor->y_axis_enabled`
  Returns `true` if the Y axis is active.

* `$sensor->z_axis_enabled`
  Returns `true` if the Z axis is active.

* `$sensor->power_mode`
  Returns the current `MSA311PowerMode`.
  Possible values: `NORMAL`, `LOW_POWER`, `SUSPEND`.

* `$sensor->bandwidth`
  Returns the current `MSA311Bandwidth` low-power filter bandwidth.
  Possible values: `HZ1_95`, `HZ3_9`, `HZ7_81`, `HZ15_63`, `HZ31_25`, `HZ62_5`, `HZ125`,
  `HZ250`, `HZ500`.

* `$sensor->range_resolution`
  Reads and returns the full `MSA311RangeResolution` data object.

* `$sensor->output_data_rate_register`
  Reads and returns the full `MSA311OutputDataRate` data object (data rate plus per-axis
  enable flags).

* `$sensor->power_mode_bandwidth`
  Reads and returns the full `MSA311PowerModeBandwidth` data object.

* `$sensor->interrupt_set0`
  Reads and returns the full `MSA311InterruptSet0` data object (orientation / tap / active
  interrupt enables).

* `$sensor->interrupt_set1`
  Reads and returns the full `MSA311InterruptSet1` data object (new-data and freefall
  interrupt enables).

* `$sensor->interrupt_map0`
  Reads and returns the full `MSA311InterruptMap0` data object (event-to-INT1 routing).

* `$sensor->tap_config`
  Reads and returns the full `MSA311TapConfig` data object (tap quiet/shock timing and the
  double-tap window).

Writable Properties (Setters)
-----------------------------

* `$sensor->range = MSA311Range::G8;`
  Sets the full-scale measurement range.

* `$sensor->resolution = MSA311Resolution::BIT12;`
  Writes the resolution field of the RANGE_RESOLUTION register.

* `$sensor->data_rate = MSA311DataRate::HZ250;`
  Sets the output data rate.

* `$sensor->x_axis_enabled = true;`
  Enables or disables the X axis.

* `$sensor->y_axis_enabled = true;`
  Enables or disables the Y axis.

* `$sensor->z_axis_enabled = true;`
  Enables or disables the Z axis.

* `$sensor->power_mode = MSA311PowerMode::NORMAL;`
  Sets the device power mode.

* `$sensor->bandwidth = MSA311Bandwidth::HZ125;`
  Sets the low-power-mode filter bandwidth.

* `$sensor->range_resolution = new MSA311RangeResolution(...);`
  Writes the full RANGE_RESOLUTION register from a data object.

* `$sensor->output_data_rate_register = new MSA311OutputDataRate(...);`
  Writes the full ODR register from a data object.

* `$sensor->power_mode_bandwidth = new MSA311PowerModeBandwidth(...);`
  Writes the full POWER_MODE_BANDWIDTH register from a data object.

* `$sensor->interrupt_set0 = new MSA311InterruptSet0(...);`
  Writes the full INT_SET_0 register from a data object.

* `$sensor->interrupt_set1 = new MSA311InterruptSet1(...);`
  Writes the full INT_SET_1 register from a data object.

* `$sensor->interrupt_map0 = new MSA311InterruptMap0(...);`
  Writes the full INT_MAP_0 register from a data object.

* `$sensor->tap_config = new MSA311TapConfig(...);`
  Writes the full TAP_DURATION register from a data object.

Methods
-------

* `$sensor->getAcceleration(): array`
  Reads all three axes (each as a separate 2-byte transfer) and returns a `[x, y, z]` array
  in m/s². This is the method backing the `acceleration` property and the
  `GenericAccelerometer` contract.

* `$sensor->enableTapDetection(int $tap_count = 1, int $threshold = 25, bool $long_initial_window = true, bool $long_quiet_window = true, MSA311TapDuration $double_tap_window = MSA311TapDuration::MS250): void`
  Configures single- (`tap_count: 1`) or double-tap (`tap_count: 2`) detection and enables the
  matching interrupt. Throws `InvalidArgumentException` for any other tap count.
