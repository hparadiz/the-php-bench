# Doctrine Adapter Notes

## Status

This adapter still references [Tests.php](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Doctrine/Tests.php)'s removed `Entity\Employee` class and needs a full rebuild against the canonical model families.

## Implementation details to preserve during the rebuild

- Doctrine stages writes in the unit of work. `insert()` and `update()` call `persist()`, while `flush()` performs the actual database write.
- Deletes are also deferred until `flush()`.
- Reads use `EntityManager::find()` by primary key.
- Query and metadata caches are backed by in-memory Symfony array adapters in this harness.
- Attribute metadata is already wired up, which fits the new benchmark model families well.

## Rebuild requirements

- Add one entity per canonical family under `src/Doctrine/Entity`.
- Keep the canonical seeded field names from [ScenarioRegistry.php](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Seed/ScenarioRegistry.php).
- Be explicit about Doctrine types where they matter: `decimal`, `date`, `datetime`, boolean, and large text.
- If Doctrine supports sequence or identity generation differently across MySQL, MariaDB, PostgreSQL, and SQLite, document the chosen strategy.

## Things to document once rebuilt

- Whether decimals are hydrated as strings, which is Doctrine's normal behavior and can affect fairness if other ORMs coerce to float.
- Whether date and datetime fields are mutable objects, immutable objects, or strings.
- Whether `flush()` is called once per phase or more frequently, and why.
- Whether batch insertion optimizations are intentionally avoided or used.
