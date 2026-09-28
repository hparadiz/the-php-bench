# Benchmark Spec

This document is the implementation-level companion to `docs/benchmark-expansion-plan.md`.

Its purpose is to lock down:

- the exact model families
- field definitions
- indexed variants
- seed rules
- scenario naming
- run-batch behavior
- output naming
- legacy assets that should be removed after migration

This spec should be treated as the source of truth for the new benchmark matrix.

## Supported Backends

The benchmark matrix is defined for:

- SQLite
- MySQL
- MariaDB
- PostgreSQL

If a specific ORM cannot support one backend or one field type, that gap must be explicit in the scenario manifest and in result reporting.

## Engine Provisioning

Server-based engines must be provisioned through repository-managed containers.

Containerized engines:

- MySQL
- MariaDB
- PostgreSQL

SQLite remains local because it is embedded.

The container spec should define:

- engine version
- port mapping
- database name
- username
- password
- startup health check

The benchmark should not depend on ad hoc host-installed database services for these backends.

## Canonical Model Families

We will define five canonical benchmark model families.

These are logical model definitions, not ORM-specific class definitions.

## 1. Canary

Scenario ID prefix:

- `canary`

Purpose:

- one-of-each-field benchmark
- mapping and casting stress case
- widest practical record shape in the benchmark

### Field Set

- `id`: integer primary key autoincrement
- `bool_flag`: boolean
- `small_int`: small integer
- `int_value`: integer
- `big_int`: big integer
- `float_value`: float
- `double_value`: double
- `decimal_value`: decimal(12,4)
- `fixed_char_8`: char(8)
- `fixed_char_16`: char(16)
- `string_short`: varchar(32)
- `string_medium`: varchar(128)
- `string_long`: varchar(512)
- `text_value`: text
- `date_value`: date
- `datetime_value`: datetime/timestamp equivalent
- `nullable_string`: varchar(64), nullable
- `nullable_int`: integer, nullable

### Indexed Variant

Indexed columns:

- `string_short`
- `int_value`
- `date_value`

### Unindexed Variant

Only primary key index.

## 2. IntFixed

Scenario ID prefix:

- `int_fixed`

Purpose:

- pure fixed-width integer workload
- scalar persistence baseline

### Field Set

- `id`: integer primary key autoincrement
- `i01` through `i12`: integer, not null

Optional backend-specific refinement:

- use smaller integer widths where the ORM can express them cleanly
- otherwise normalize all to integer columns

### Indexed Variant

Indexed columns:

- `i01`
- `i06`
- `i12`

### Unindexed Variant

Only primary key index.

## 3. FloatFixed

Scenario ID prefix:

- `float_fixed`

Purpose:

- fixed-width numeric benchmark
- floating-point and decimal mapping benchmark

### Field Set

- `id`: integer primary key autoincrement
- `f01`: float
- `f02`: float
- `d01`: double
- `d02`: double
- `n01`: decimal(10,2)
- `n02`: decimal(12,4)
- `n03`: decimal(18,6)
- `n04`: decimal(20,8)

### Indexed Variant

Indexed columns:

- `n01`
- `n03`

### Unindexed Variant

Only primary key index.

## 4. StringFixed

Scenario ID prefix:

- `string_fixed`

Purpose:

- fixed-length text benchmark
- predictable-width string hydration benchmark

### Field Set

- `id`: integer primary key autoincrement
- `c08_01`: char(8)
- `c08_02`: char(8)
- `c16_01`: char(16)
- `c16_02`: char(16)
- `c32_01`: char(32)
- `c32_02`: char(32)
- `c64_01`: char(64)
- `c64_02`: char(64)

If an ORM cannot cleanly differentiate `char` from `varchar`, the adapter may map to fixed-length semantic values stored in `varchar`, but that downgrade must be documented.

### Indexed Variant

Indexed columns:

- `c08_01`
- `c16_01`
- `c32_01`

### Unindexed Variant

Only primary key index.

## 5. StringVariable

Scenario ID prefix:

- `string_variable`

Purpose:

- application-like variable text benchmark
- replacement for the old `Employee` benchmark shape

### Field Set

- `id`: integer primary key autoincrement
- `name`: varchar(64)
- `title`: varchar(128)
- `email`: varchar(160)
- `company`: varchar(160)
- `city`: varchar(64)
- `region`: varchar(64)
- `postal_code`: varchar(32)
- `country`: varchar(64)
- `phone`: varchar(32)
- `notes`: text

### Indexed Variant

Indexed columns:

- `email`
- `company`
- `postal_code`

### Unindexed Variant

Only primary key index.

## Row Count Profiles

Each model family must run with:

- `1`
- `100`
- `500`

These are explicit scenario dimensions.

They should not be hidden as a generic benchmark iteration knob.

## Run Batch Profiles

Each ORM/backend/scenario combination must run in a repeated batch.

Default batch size:

- `10`

Each run in the batch must have:

- `run_number`
- `warmup`

### Warmup Labeling

Run labeling:

- run `1`: `warmup = true`
- runs `2` through `10`: `warmup = false`

Warmup data must remain visible in raw results.

It must not be silently discarded.

## Execution Order

For a given ORM/backend/scenario:

1. provision or reset the database state
2. execute run 1 and label it warmup
3. execute runs 2 through 10 as measured repeat runs
4. persist raw results for all 10 runs
5. compute aggregates for the non-warmup runs

The benchmark should make it easy to decide later whether the database is reset between runs or only between scenario batches, but raw output must always reveal which run number produced which result.

## Seed Rules

Seed values must be deterministic and generated from:

- model family
- row count
- row ordinal
- field name

The same logical row must resolve to the same logical values across backends.

## Seed Generation Contract

The seeder must expose a logical row generator with this shape:

- input:
  - model family
  - row count
  - row ordinal
- output:
  - associative field map
  - expected update map
  - expected read assertions

No randomness should be used during timed benchmark phases.

If pseudo-randomness is used to make values look realistic, it must come from a fixed seed.

## Field-Level Seed Rules

### Booleans

Use alternating deterministic values:

- odd rows: `true`
- even rows: `false`

### Integers

Use stable arithmetic progressions:

- `i01 = row`
- `i02 = row * 10`
- `i03 = row * 100`

and so on.

### Floats And Doubles

Use deterministic fractional formulas:

- `f01 = row + 0.25`
- `f02 = row * 1.5 + 0.125`
- `d01 = row / 3.0`
- `d02 = row * 10.0 + 0.875`

### Decimals

Use string-safe decimal values generated from row number to avoid backend drift.

Example pattern:

- `n01 = sprintf('%d.%02d', row, row % 100)`

### Fixed Strings

Generate exact-width padded values.

Example:

- `c08_01 = str_pad('A' . row, 8, 'X')`

### Variable Strings

Generate bounded but varying lengths using row ordinal.

Example:

- `name = "Name {$row}"`
- `company = "Company {$row}"`
- `notes = repeated deterministic text with length bucket based on row % 5`

### Dates

Generate deterministic offsets from a fixed anchor date.

Anchor:

- `2020-01-01`

### Datetimes

Generate deterministic offsets from a fixed anchor timestamp.

Anchor:

- `2020-01-01 00:00:00`

### Nullable Fields

Use a simple stable nulling rule:

- every 5th row is `null`
- all other rows get deterministic non-null values

## Update Rules

Every model family must define deterministic update transformations.

These transformations must avoid changing indexed-vs-unindexed semantics other than the normal database cost of maintaining indexes.

### Update Pattern

- integers: add a fixed offset
- floats/doubles/decimals: add a fixed fractional offset
- fixed strings: replace with another exact-width deterministic value
- variable strings: prefix or suffix with stable marker text
- nullable fields: do not toggle nullness during the main update benchmark unless the scenario explicitly exists to test null transitions

## Read Patterns

The benchmark should support:

- sequential read by primary key
- random read by primary key
- optional indexed lookup reads in a later phase

For the first implementation, primary-key read patterns are sufficient as long as indexed and unindexed schema variants exist.

## Scenario Naming

Scenario IDs should be machine-readable and stable.

Format:

- `{model_family}.{row_count}.{index_mode}`

Examples:

- `canary.1.indexed`
- `canary.500.unindexed`
- `int_fixed.100.indexed`
- `string_variable.500.unindexed`

Backend remains separate from scenario ID and belongs in result dimensions.

## Output Columns

The result output should include:

- `orm`
- `backend`
- `scenario_id`
- `model_family`
- `row_count`
- `index_mode`
- `run_number`
- `warmup`
- `init_time`
- `insert_time`
- `read_time`
- `update_time`
- `update_verify_time`
- `random_read_time`
- `delete_time`
- `total_runtime`

If aggregate output is produced, it should include:

- `aggregate_scope`
- `aggregate_run_start`
- `aggregate_run_end`
- `aggregate_excludes_warmup`
- `aggregate_kind`

Example aggregate kinds:

- `mean`
- `median`
- `min`
- `max`
- `stddev`

## Seeder Responsibilities

The new seeder must be responsible for:

- generating logical schema specs
- creating backend-specific DDL from those specs
- generating deterministic rows
- loading seed rows
- loading update expectations
- exposing row identifiers for read/delete phases
- working against SQLite plus containerized MySQL, MariaDB, and PostgreSQL

The seeder should not:

- rely on `northwind/`
- import the old sample SQL files
- depend on old `Employee`-specific business naming

## Legacy Retirement List

These are the assets we intend to remove after the new benchmark is validated.

### Must Remove

- `northwind/`
- the old `Employee` benchmark model family
- the legacy business-domain model set that only exists to mirror the old sample schema

### Likely Remove Or Replace

- old schema-loader code paths built around importing Northwind SQL
- old seed/setup helpers tied to the sample database
- unused ORM-specific generated model sets for tables we no longer benchmark

## ORM Skip Policy

Skips are allowed only when one of these is true:

- the ORM cannot support the backend
- the ORM cannot support the field type in a semantically acceptable way
- the ORM cannot express the indexed schema variant without changing the logical scenario

Every skip must be:

- declared
- documented
- visible in result output

Silent downgrades are not acceptable.

In practice, PostgreSQL support will likely be one of the first places this policy matters, because not every current adapter supports it today.

## Warmup And Aggregate Policy

The benchmark must support both of these views:

- raw per-run results
- aggregate per-scenario results

The default aggregate policy should be:

- exclude run 1 warmup
- aggregate runs 2 through 10

Warmup should still be shown in dashboards, tables, and CSV output as an explicit row-level fact.

## Initial Implementation Order

The first concrete implementation should define:

1. `canary.1.indexed`
2. `canary.100.indexed`
3. `string_variable.1.indexed`
4. `string_variable.100.indexed`

Then expand to:

- unindexed variants
- `500` row scenarios
- `int_fixed`
- `float_fixed`
- `string_fixed`

And from the first implementation onward, each scenario should already run as a 10-run batch with explicit warmup labeling.

This gives us early coverage without forcing the whole matrix to land at once.

## Open Decisions

These details still need a final call before implementation starts:

- whether `random read` should remain primary-key-only in v1 of the new matrix
- whether indexed scenarios also get dedicated indexed-column lookup phases
- whether `decimal` should be mandatory in `Canary` for every ORM, or optional when the ORM/backend combination is weak there
- how much adapter-level code generation we want for the new model families
- which exact container images and versions we standardize on for MySQL, MariaDB, and PostgreSQL
- whether database state is recreated between every run in a 10-run batch or once per batch
