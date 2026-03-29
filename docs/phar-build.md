# PHAR Build Plan

The benchmark runtime split is now aligned with PHAR packaging.

## Targets

- controller PHAR
  - contains the main CLI, result rendering, upload, and benchmark orchestration
- runtime PHAR
  - one per framework or per framework variant
  - contains only that runtime's worker bootstrap, vendor tree, and shared benchmark core files
- bundle PHAR
  - contains the controller payload plus any selected runtime payloads in one extracted root

## Current Commands

- `./bin/the-php-bench phar:build-controller`
- `./bin/the-php-bench runtime:build <Framework>`
- `./bin/the-php-bench phar:build-bundle <bundle-name> [Framework ...]`

All builders currently require:

```bash
php -d phar.readonly=0 ./bin/the-php-bench ...
```

## Runtime Variants

Treat each framework variant as its own runtime artifact, not as a flag inside one runtime.

Examples:

- `Doctrine`
- `Doctrine-DBAL44`
- `Doctrine-DBAL45`
- `Eloquent-v13`
- `Eloquent-dev`

That keeps lockfiles, signatures, and reproducibility clean.

## Build Inputs

The build manifests are derived in code from:

- `src/Build/ManifestRepository.php`
- `src/Build/PharCompiler.php`

The compiler packages a virtual extracted root instead of trying to run frameworks directly out of `phar://`.

## Current State

Implemented:

- runtime/controller/bundle manifest generation
- PHAR compiler with extracted-root stub generation
- build commands for controller/runtime/bundle artifacts
- versioned PHAR filenames for controller, runtime, and bundle outputs

Still to harden:

- finish validating the extracted-runtime stub path on generated PHARs
- build controller and bundle artifacts end to end
- teach bundle/controller builds to reference packaged runtime artifacts for release output
- add release metadata and checksums
