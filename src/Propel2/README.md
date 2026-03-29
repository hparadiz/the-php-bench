# Propel2 Adapter Notes

## Status

This adapter is restored for the canonical benchmark families using generated classes under [src/Propel2](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Propel2).

## Implementation details

- The Propel object model is generated from [propel/schema.xml](/home/akujin/Divergence/php-orm-sql-benchmarks/propel/schema.xml).
- Runtime table creation is no longer owned by Propel schema SQL in this repo. The benchmark harness creates tables from the seeded canonical schema, and Propel uses its generated metadata for mapping and query behavior.
- The active benchmark harness currently uses `StringVariable` as the first converted family while the rest of the scenario matrix is being wired through configuration.

## Regeneration

- Rebuild the Propel model surface with:
  `vendor/bin/propel model:build --config-dir=propel --schema-dir=propel --output-dir=/tmp/php-orm-propel-build`
- Then copy `/tmp/php-orm-propel-build/ThePHPBench/Propel2` into [src/Propel2](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Propel2).

Any schema change to the canonical families should update `propel/schema.xml` first, then regenerate.
