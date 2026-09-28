# Benchmark Expansion Plan

This document defines a plan for replacing the current single-model benchmark with a broader benchmark matrix that better exercises ORM mapping, hydration, persistence, indexing, and seed-data behavior.

The current suite is still useful, but it is narrowly centered on one `Employee` lifecycle pattern. That means it mostly measures:

- one record shape
- one table width
- one mix of field types
- one indexing pattern
- one cardinality profile

That is not enough to make strong claims about ORM behavior in real applications.

## Goals

- Add multiple model shapes instead of relying on `Employee` alone
- Measure how ORMs behave across narrow vs wide records
- Measure fixed-width vs variable-width field behavior
- Measure indexed vs non-indexed access paths
- Measure behavior against multiple dataset sizes
- Measure warmup behavior and repeated-run dynamics instead of treating a single run as authoritative
- Keep results comparable across ORMs and SQL backends
- Standardize database engine setup with containerized services
- Add PostgreSQL to the supported backend matrix
- Replace the old sample database and schema assets with generated benchmark data
- Remove legacy benchmark models and fixtures that are no longer part of the active benchmark surface

## Containerized Database Engines

To make the benchmark fair and reproducible, the supported SQL servers should run from repository-managed containers instead of depending on whatever happens to be installed on the host.

This should become the default benchmark environment for server-based backends.

Rationale:

- consistent versions across runs and machines
- explicit engine configuration
- repeatable startup and teardown
- easier CI integration
- easier expansion to additional backends such as PostgreSQL

SQLite remains local because it is an embedded engine, but MySQL, MariaDB, and PostgreSQL should be run through containers.

The plan should assume:

- a checked-in container definition
- stable ports and credentials for local benchmark runs
- one documented command path for bringing engines up and down

## Add PostgreSQL

PostgreSQL should be part of the new benchmark target set.

That changes the supported backend list from:

- SQLite
- MySQL
- MariaDB

to:

- SQLite
- MySQL
- MariaDB
- PostgreSQL

This matters early because:

- field specs need to stay inside a common subset that PostgreSQL can represent cleanly
- the seeder must emit equivalent logical data for PostgreSQL too
- ORM skip policy needs to account for adapters that cannot support PostgreSQL

## Retire Northwind And The Legacy Sample Database

The `northwind/` directory and the current sample database must be treated as legacy assets.

They are not sufficient for the benchmark we actually want to run because they are:

- business-domain shaped instead of benchmark-shaped
- too tied to the old `Employee`-centric workload
- full of tables and models we do not benchmark directly
- not designed around the new matrix dimensions

This means the migration should explicitly end with:

- removing `northwind/`
- removing the old `Employee` model as the benchmark baseline
- removing the other unused legacy benchmark models that only exist to support the old sample schema

We should not design the new benchmark around preserving backward compatibility with that schema.

## Build A Real Seeder First

Before implementing the new benchmark matrix, we need a database seeder that can generate fake benchmark data for every supported SQL backend.

Supported backends today:

- SQLite
- MySQL
- MariaDB
- PostgreSQL

The seeder should become the new source of truth for benchmark data setup.

### Why This Comes First

If we keep relying on the old sample schema and ad hoc setup, we will end up carrying dead structures into the new matrix.

The seeder-first approach gives us:

- benchmark-shaped data instead of application-shaped sample data
- deterministic setup across all supported backends
- a clean path to retire the old sample database entirely
- repeatable fixture generation for every model family, row count, and index mode

### Seeder Scope

The seeder must be able to:

- create schema for each model family
- create indexed and unindexed variants
- generate deterministic fake rows
- populate `1`, `100`, and `500` row scenarios
- target SQLite, MySQL, MariaDB, and PostgreSQL with equivalent logical data

### Seeder Versus Insert-Test Setup

It is possible that some insert scenarios could build data on the fly inside the benchmark itself, but that should not be the default design.

The safer planning assumption is:

- use a dedicated seeder for baseline data generation
- keep measured benchmark phases focused on the operations being tested
- avoid mixing fixture construction logic into the timed insert path unless we intentionally want to benchmark object creation overhead there

That keeps the benchmark honest and makes the scenarios reproducible.

## Proposed Model Families

These model families should become the new benchmark foundation.

### 1. Canary

Use Divergence's `Canary` concept as the "one of each field type" model.

Purpose:

- broadest schema coverage
- catches mapping and casting overhead
- exposes expensive hydration paths
- highlights ORM behavior around nullable and mixed field types

This should be the benchmark's "field variety" case, not the default baseline for everything.

### 2. IntFixed

A narrow or medium-width model containing only fixed-width integer fields.

Purpose:

- lowest-complexity scalar benchmark
- isolates overhead that is not caused by strings or variable-length fields
- useful as a near-floor for typed scalar persistence

Suggested field mix:

- primary key
- 8 to 16 integer columns
- signed and unsigned variants if the ORM/backend supports it cleanly

### 3. FloatFixed

A model containing fixed-width numeric columns:

- `float`
- `double`
- fixed-precision decimal columns where supported

Purpose:

- surfaces numeric conversion cost
- tests precision-sensitive ORM mapping paths
- helps separate integer-heavy performance from real-number-heavy performance

### 4. StringFixed

A model composed only of fixed-length string columns.

Purpose:

- tests predictable-width text storage
- exposes overhead from text hydration without large blob-style payloads

Suggested field mix:

- `char`
- short fixed `varchar` values used with constant-length generated data

### 5. StringVariable

This is the successor to the current `Employee`-style model.

Purpose:

- realistic application-style text workload
- multiple variable-length strings
- still easy to compare with the existing benchmark history

This should likely replace `Employee` semantically rather than carry the business-domain naming forward.

## Dataset Size Matrix

The benchmark should run each model family at these row counts:

- 1
- 100
- 500

Rationale:

- `1` isolates per-operation overhead and cold-start effects
- `100` shows steady-state ORM behavior without becoming too noisy
- `500` adds enough volume to expose scaling issues while staying practical for repeated runs

These should be treated as explicit benchmark scenarios, not just a configurable iteration count hidden in `config/config.php`.

## Index Matrix

Each model family should be tested in two schema variants:

- `indexed`
- `unindexed`

Rationale:

- ORMs are often judged on read/update performance without accounting for index cost
- indexed and unindexed tables answer different questions
- insert costs especially change once indexes exist

Important constraint:

The indexed and unindexed variants must differ only in index presence, not column shape or seed logic.

## Benchmark Matrix Shape

The full benchmark dimension becomes:

- ORM
- SQL backend
- model family
- row count
- index mode
- run number
- warmup flag

For the proposed five model families, three row counts, and two index modes, that is:

- `5 x 3 x 2 = 30` schema scenarios per ORM/backend pair

If each scenario is executed 10 times, that becomes:

- `30 x 10 = 300` measured runs per ORM/backend pair

That is already a large benchmark expansion, so implementation must be phased and the reporting layer must support summarization cleanly.

## Repeated Runs And Warmup

A single run is not sufficient.

The benchmark should execute each ORM/backend/scenario combination multiple times and explicitly show which run is considered the warmup.

Default design target:

- `10` runs per ORM/backend/scenario

Why this matters:

- some ORMs pay a large first-run metadata or reflection cost
- some ORMs warm caches aggressively after the first run
- some ORMs show unstable behavior across repeated runs
- some backends may exhibit startup or planner effects that only appear across multiple passes

The benchmark should preserve that information instead of averaging it away.

### Warmup Policy

The first run should be labeled as:

- `warmup = true`

Subsequent runs should be labeled as:

- `warmup = false`

The benchmark should not hide warmup results. They are a real part of ORM behavior and are interesting to compare.

### Reporting Expectations

We should report both:

- every individual run
- aggregate statistics excluding warmup

Suggested aggregates:

- min
- max
- mean
- median
- standard deviation

for runs `2` through `10`

## Seed Data Requirements

We need deterministic seed generation for every scenario.

### Seed Principles

- deterministic values
- identical logical data across ORMs
- schema-aware generators
- no random runtime generation during measured sections

### Seed Strategy

Each model family should have a dedicated seed generator that can emit:

- the insert dataset
- the expected read/update assertions
- indexed lookup targets
- random-read target lists

The seed layer should generate data once per scenario before timing begins.

This should be implemented as a real backend-aware seeder, not as leftover logic tied to the old Northwind import flow.

### Seed Profiles

At minimum, each scenario should seed:

- base insert rows for the target row count
- deterministic update values
- deterministic read verification values
- deterministic random-read keys

## Measurement Strategy

The benchmark should continue to measure inserts, reads, updates, and deletes, but the scenario setup needs to become more explicit.

Recommended measured phases:

- schema setup
- seed insert
- sequential read
- update
- update verification
- random indexed read
- delete

For indexed vs unindexed scenarios, "random read" becomes especially important.

Those measured phases should exist per run, not just per scenario.

## Proposed Architecture Changes

The current benchmark code is organized around one `Tests` class per ORM with hardcoded model usage. That structure will not scale cleanly for the expanded matrix.

We should move to:

- scenario definitions
- shared dataset generators
- ORM adapters that bind a scenario to ORM-specific model classes
- backend-specific schema/seeder writers that all consume the same logical model specs
- container-managed engine orchestration for MySQL, MariaDB, and PostgreSQL
- run-batch orchestration that executes each scenario repeatedly and labels warmup explicitly

### New Core Concepts

#### Scenario

A scenario defines:

- model family
- row count
- index mode
- backend constraints
- schema source
- seed generator

#### ModelSpec

A model spec defines:

- logical field set
- field types
- indexed fields
- default row generator

#### ORM Adapter

An ORM adapter should implement the scenario using ORM-specific model classes, but the logical scenario must stay backend-agnostic.

## Recommended Rollout Phases

Trying to land the full matrix in one pass is too risky. Do it in phases.

### Phase 1: Seeder And Scenario Framework

- add containerized engine definitions for MySQL, MariaDB, and PostgreSQL
- build the backend-aware fake-data seeder for SQLite, MySQL, MariaDB, and PostgreSQL
- introduce scenario objects
- introduce run-batch execution with explicit warmup labeling
- separate scenario selection from ORM adapters
- keep current `Employee` benchmark working during the refactor

Deliverable:

- the benchmark can run one ORM against one scenario definition using generated seed data across a repeated-run batch

### Phase 2: First Two Families

Implement:

- `Canary`
- `StringVariable`

Reason:

- highest information gain
- easiest bridge from current benchmark
- validates mixed-field and application-like cases early

### Phase 3: Numeric Families

Implement:

- `IntFixed`
- `FloatFixed`

Reason:

- gives us scalar-focused comparisons
- shows how much cost comes from text and mixed field casting

### Phase 4: Fixed String Family

Implement:

- `StringFixed`

Reason:

- completes the field-shape set
- distinguishes predictable text storage from variable text workloads

### Phase 5: Index Variants

Add:

- indexed schema variants
- unindexed schema variants

Reason:

- this doubles the scenario count, so it should come after the scenario machinery is already stable

### Phase 6: Full Matrix Activation

Enable:

- row counts `1`, `100`, `500`
- all model families
- both index modes

Then update result reporting to group by scenario, not just by ORM/backend.

### Phase 7: Legacy Removal

After the new benchmark matrix is validated:

- remove `northwind/`
- remove the old `Employee` benchmark model
- remove the legacy unused models that were only there to mirror the old sample database
- remove old schema-loading code paths that exist only for Northwind import

This removal should be part of the plan, not left as optional cleanup.

## Reporting Changes Needed

The existing CSV layout is too flat for the expanded benchmark.

Results should include explicit columns for:

- ORM
- backend
- model family
- row count
- index mode
- init time
- insert time
- read time
- update time
- update verification time
- random read time
- delete time
- total runtime

Without these dimensions in the output, the new benchmark will be hard to analyze.

## Risks

### 1. Matrix Explosion

The matrix can become too expensive to run regularly.

Mitigation:

- keep quick smoke scenarios
- allow filtered runs by model family, row count, and index mode

### 2. ORM Feature Mismatch

Some ORMs may not support identical field definitions or indexing semantics cleanly.

Mitigation:

- define a strict common subset first
- document any ORM-specific downgrades explicitly

### 3. Seed Drift

If each adapter generates its own values independently, results stop being comparable.

Mitigation:

- centralize scenario seed generation

### 4. Schema Maintenance Cost

Manually maintaining five model families across many ORMs can get expensive.

Mitigation:

- generate model code where practical
- keep logical model specs as the source of truth

## Recommendation

This direction is correct, but it needs to be treated as a benchmark redesign rather than an incremental tweak.

The best implementation order is:

1. build the cross-backend fake-data seeder
2. build a scenario layer
3. implement `Canary` and `StringVariable`
4. add row count variants
5. add indexed vs unindexed variants
6. add the remaining fixed-width model families
7. remove `northwind/`, `Employee`, and the unused legacy model set

That preserves momentum while preventing the codebase from collapsing under a full matrix rewrite done all at once.

## Immediate Next Step

Before writing code, we should produce a follow-up spec that locks down:

- exact field lists for each model family
- exact indexed columns for each indexed variant
- deterministic seed rules for each field type
- naming conventions for scenario IDs and output rows
- which ORMs are allowed to skip which scenarios, if any
- the exact retirement list for `northwind/`, `Employee`, and the other legacy models
