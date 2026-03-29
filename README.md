# The PHP Bench

The PHP Bench is a benchmark harness for comparing PHP ORM behavior across multiple database engines under the same workload.

Current ORM coverage:

- ActiveRecord
- Cake
- CakeCached
- Doctrine
- Eloquent
- PHPFUI
- PHPFUIBatch
- Propel2
- RedBean
- DivergenceV2
- DivergenceV3

Current database coverage:

- SQLite
- MariaDB
- MySQL
- PostgreSQL

## Current State

This repo is in the middle of a benchmark redesign.

The active benchmark path is moving away from the legacy sample schema and toward canonical scenario families backed by deterministic schema generation and seeding work. The planned model families are documented in:

- `docs/benchmark-expansion-plan.md`
- `docs/benchmark-spec.md`

The benchmark runner already supports:

- isolated subprocess execution per benchmark
- isolated per-framework runtime worker paths
- isolated per-framework Composer vendor trees under `runtimes/*`
- per-framework runtime PHAR packaging
- extracted-root execution from built PHARs so each runtime executes alone
- repeated runs per benchmark
- explicit warmup labeling on run 1
- filtered execution by ORM or ORM/backend label
- CSV result capture
- terminal result rendering

What makes this benchmark materially different from the usual "one repo, one autoloader, many adapters" setup is that each framework is treated as its own runtime artifact. We do not install every ORM into one shared `vendor/` and then hope autoloading, shared transitive dependencies, or bootstrap side effects do not leak across frameworks. Instead, every framework gets its own Composer install, its own lockfile, its own vendor tree, and its own worker entrypoint. That runtime is then compiled into its own PHAR and executed in isolation.

## Layout

- `bin/the-php-bench`: main CLI entrypoint
- `config/`: shared config helpers and local secrets
- `docs/`: benchmark planning and specification docs
- `src/`: runtime, adapters, and scenario code
- `storage/sqlite/`: file-backed SQLite databases

SQLite database files should never be created in the project root. File-backed SQLite paths resolve under `storage/sqlite/`.

## Install

```bash
composer install
./bin/the-php-bench runtime:install-vendors
```

`composer install` prepares only the controller app and shared benchmark code. The top-level `vendor/` is intentionally small and does not contain the ORM/framework runtimes being benchmarked. `./bin/the-php-bench runtime:install-vendors` then resolves and installs each isolated runtime under `runtimes/*`, writing a separate lockfile and vendor tree per framework.

That split is intentional: the root install is for the controller and shared benchmark code, while each framework runtime keeps its own dependency graph isolated from every other framework.

There is also a legacy seeding command:

```bash
./bin/the-php-bench runtime:seed-vendors
```

That command bluntly copies the current root `vendor/` tree into selected runtimes. It is not the normal install path, and after the root Composer trim it should not be treated as a substitute for `runtime:install-vendors`.

If you want the containerized server databases:

```bash
docker-compose up -d
```

## Secrets

Local secrets live in:

- `config/secrets.local.php`

Use the template at:

- `config/secrets.local.example.php`

Shared secrets are loaded through:

- `config/load-secrets.php`

That same shared config path is also used by the embedded Divergence configs.

## Running Benchmarks

Before running benchmarks on a machine you care about, generate the cached system manifest once:

```bash
sudo php ./bin/the-php-bench system:manifest:generate
```

This writes `system-manifest.json` in the repo root. The benchmark runner reads that file and does not attempt privileged hardware probing while benchmarks are in flight. This matters for two reasons:

- benchmark timing should not include `sudo`/SMBIOS probing noise
- result files should be reproducible and stable even when the benchmark process is running without elevated privileges

The cached manifest is where DIMM slot/speed details belong. If you change hardware, regenerate it.

Build the PHARs before running benchmarks:

```bash
./bin/the-php-bench runtime:build --all
```

Build and select a single runtime explicitly:

```bash
./bin/the-php-bench runtime:build DivergenceV3 --select
./bin/the-php-bench runtime:list DivergenceV3
./bin/the-php-bench runtime:use DivergenceV3 latest
```

Run the configured benchmark suite:

```bash
./bin/the-php-bench benchmark
```

The benchmark parent now requires each framework runtime PHAR to exist under a versioned path like `dist/runtime-<framework>-<version>.phar` and routes subprocess execution through that artifact. If a runtime PHAR is missing, the runner prints a warning and skips that framework instead of compiling or falling back on the fly. If you refresh root controller dependencies, refresh any runtime dependencies, or update either local Divergence tree, rerun:

```bash
./bin/the-php-bench runtime:install-vendors
./bin/the-php-bench runtime:build --all
```

Run a filtered benchmark:

```bash
./bin/the-php-bench benchmark DivergenceV3
./bin/the-php-bench benchmark Doctrine.MariaDB
```

Run a specific config once:

```bash
./bin/the-php-bench benchmark --config=config/first-run.php --runs=1
```

Useful benchmark options:

- `--config=...`
- `--results=...` to override the default per-run filename
- `--type=...`
- `--label=...`
- `--run-id=...`
- `--runs=10`
- `--concurrency=1`

By default, each benchmark run now writes to its own file under `results/`:

```bash
results/results-$runID-$testLabel-$timestamp.csv
```

## Viewing Results

Render terminal output from the latest run file, or from the cumulative standard-full view if matching run files exist:

```bash
./bin/the-php-bench results
```

Render a specific result file:

```bash
./bin/the-php-bench results results/results-20260327053441-19684d65-sqlite-20260327-053441.csv
```

Convert CSV to Markdown:

```bash
./bin/the-php-bench results:markdown results/results-20260327053441-19684d65-sqlite-20260327-053441.csv
```

The results view reads the run manifest embedded in each CSV and surfaces cached machine metadata from `system-manifest.json` through that manifest.

## Config Files

- `config/config.php`: main benchmark matrix
- `config/example.php`: example config surface
- `config/first-run.php`: one-per-ORM first-pass config
- `config/smoke.php`: smoke config
- `config/smoke-mariadb.php`: MariaDB smoke config
- `config/postgres.php`: PostgreSQL-focused config

## Current Focus

The main implementation work in flight is:

- completing the fresh scenario-backed ORM adapters
- validating every previously benchmarked ORM on the new path
- improving result presentation
- replacing legacy data assumptions with proper deterministic seeding

## Utilities

Operational utilities now live under `./bin/the-php-bench` commands:

- `runtime:install-vendors`
- `runtime:seed-vendors`
- `runtime:test`
- `runtime:build`
- `runtime:use`
- `runtime:list`
- `phar:build-controller`
- `phar:build-bundle`
- `system:manifest:generate`
- `results:markdown`
- `model:generate-canonical`
- `source:refresh-divergence-v3`
- `db:setup:mysql`

## PHAR Builds

The PHAR layer is part of the benchmark design, not just a release convenience. Each framework runtime is packaged separately so the thing being measured is the framework plus its own dependency tree, executed in isolation from the other runtimes. The build manifests and compiler live in:

- `src/Build/ManifestRepository.php`
- `src/Build/PharCompiler.php`

Reference notes are in:

- `docs/phar-build.md`

Current build entrypoints:

```bash
./bin/the-php-bench phar:build-controller
./bin/the-php-bench runtime:build Doctrine
composer run compile-phars
```
