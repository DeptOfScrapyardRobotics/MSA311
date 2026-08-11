# Agent guidelines — dept-of-scrapyard-robotics/msa311

## Knowledge Bundle (OKF)

This package ships an Open Knowledge Format bundle at [`.okf/`](.okf/) (excluded from Composer dist via `.gitattributes` `export-ignore`).

Before changing this package or advising on MSA311 architecture:

1. Read [`.okf/index.md`](.okf/index.md) first (progressive disclosure).
2. Open only the linked concepts needed for the task.
3. Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
4. When you learn something durable about **this package**, update the affected `.okf` concept(s) and append `.okf/log.md`.
5. Keep the `.okf` bundle at the **package root** only — do not nest extra `.okf` folders under `src/`.
6. Circuits registry semantics belong in `scrapyard-io/gpio-framework`’s `.okf`. Motion contracts belong in `scrapyard-io/waveforms`.

## Package rules (quick) — 0.7.x

- Composer: `dept-of-scrapyard-robotics/msa311` **0.7.0**. Namespace `DeptOfScrapyardRobotics\Sensors\MSA311\`.
- Requires leaf components (not kitchen-sink frameworks): `fabricate/nuts-and-bolts`, `gpio/circuits`, `gpio/contracts`, `gpio/digital`, `gpio/i2c`, `waveforms/contracts`.
- Provider: `MSA311ServiceProvider` at package root. Catalog slug `msa311`. Command `msa311:make-profile`. Sketch `msa311-smoke`.
- IC extends `SensorIC`, implements `BootSequence` + `Waveforms\Contracts\Motion\MeasuresAcceleration`; factories `i2c(...)` / `fromI2CBus(...)`.
- GeneralPurposeIO Circuit attributes only. Enums string/int-backed, FULLY UPPERCASE cases; no class constants; prefer `is_null`.
- I2C addr **0x62**; PARTID **0x01** expect **0x13**; `x()/y()/z()` in **g**.
