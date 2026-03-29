# CakeCached Adapter Notes

## Status

This adapter inherits [Cake/Tests.php](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Cake/Tests.php) and is not rebuilt yet. It currently provides no distinct model implementation beyond the base Cake adapter.

## Intended role

- This adapter exists to measure Cake with metadata or framework-level caching enabled once the new model families are in place.
- It should use the same canonical schema as the plain Cake adapter so the only meaningful difference is caching behavior.

## Rebuild requirements

- Reuse the new Cake model layer rather than forking field definitions.
- Document exactly which caches are enabled, their scope, and whether warmup runs are expected to benefit disproportionately.
- Keep any cache toggles isolated in the adapter bootstrap so the benchmark execution path stays comparable.

## Things to document once rebuilt

- Which cache layers are active: metadata, query compilation, hydration, or other framework caches.
- Whether warmup behavior materially differs from uncached Cake and why.
