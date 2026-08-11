---
type: Module
title: Package (0.7)
description: dept-of-scrapyard-robotics/msa311 Composer identity, namespace, and discovery.
resource: composer.json
tags: [orientation, package, 0.7, msa311]
generated: { by: cursor-agent/grok-4.5, at: "2026-08-11T19:56:00Z" }
verified: { by: null, at: null }
status: draft
sources:
  - id: composer
    resource: composer.json
    title: Package composer.json
  - id: provider
    resource: src/MSA311ServiceProvider.php
    title: MSA311ServiceProvider
  - id: gitattributes
    resource: .gitattributes
    title: Dist export-ignore
---

# Identity

| Field | Value |
|-------|-------|
| Composer | `dept-of-scrapyard-robotics/msa311` **0.7.0** |
| PHP | `^8.4\|^8.5\|^8.6` |
| Namespace | `DeptOfScrapyardRobotics\Sensors\MSA311\` → `src/` |
| Provider | `DeptOfScrapyardRobotics\Sensors\MSA311\MSA311ServiceProvider` (package root, not `Providers/`) |
| Catalog slug | `msa311` |

# Requires

| Package | Constraint |
|---------|------------|
| `fabricate/nuts-and-bolts` | `^0.7.0` |
| `gpio/circuits` | `^0.7.0` |
| `gpio/contracts` | `^0.7.0` |
| `gpio/digital` | `^0.7.0` |
| `gpio/i2c` | `^0.7.0` |
| `waveforms/contracts` | `^0.7.0` |

Suggested (optional): `microscrap/i2c`, `microscrap/mpsse` at `^0.7.0`.[^composer]

# Discovery

`extra.scrapyard-io.providers` lists `MSA311ServiceProvider`. That provider registers the catalog IC, wires `msa311:make-profile` into `circuit:make-profile`, and registers the `msa311-smoke` sketch.[^provider]

# Dist

`.okf/` and `AGENTS.md` are `export-ignore` — Composer dist tarballs omit them.[^gitattributes]

# Related

* [MSA311 IC](../core/msa311.md)
* [Circuits integration](../core/circuits.md)

[^composer]: Package composer.json
[^provider]: MSA311ServiceProvider
[^gitattributes]: Dist export-ignore
