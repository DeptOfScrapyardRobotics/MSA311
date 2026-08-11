---
okf_version: "0.2"
---

# dept-of-scrapyard-robotics/msa311 Knowledge Bundle

Package knowledge for `dept-of-scrapyard-robotics/msa311` (MSA311 3-axis accelerometer driver, v0.7.x).
Read this index first; open only the concepts needed for the task.

**Trust rule:** Prefer `status: stable`. Treat `deprecated` as historical only. New agent-written concepts stay `status: draft` until a human verifies them.
**Placement:** Package-root `.okf/` only — never under `src/`.
**Links:** Concept cross-links use paths relative to each file.
**Scope:** This package’s IC surface, Circuits catalog registration, profiles, and smoke sketch. Registry semantics live in `scrapyard-io/gpio-framework`. Motion contract `MeasuresAcceleration` lives in `scrapyard-io/waveforms`.
**Dist note:** `.okf/` and root `AGENTS.md` are `export-ignore` in `.gitattributes`.

# Orientation

* [Package (0.7)](orientation/package.md) - Composer identity, namespace, provider, dependencies.

# Core

* [MSA311 IC](core/msa311.md) - SensorIC, PARTID, range scale, I2C factories, MeasuresAcceleration.
* [Circuits integration](core/circuits.md) - Catalog slug, make-profile, profiles, smoke sketch.

# Log

* [Directory update log](log.md)
