# Cake Adapter Notes

## Status

This adapter is also still on the removed `Employee` model path. [Tests.php](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Cake/Tests.php) currently references `\ThePHPBench\Cake\Table\Employee`, so it is not aligned with the new benchmark families yet.

## Implementation details to preserve during the rebuild

- Cake opens one transaction per phase and commits in `flush()`.
- Deletes are batched with `deleteAll(['employee_id IN' => $this->deletes])`.
- Reads use `Table::get($id)` and treat exceptions as misses.
- Driver selection already special-cases PostgreSQL by mapping `pgsql` to Cake's `Postgres` driver class.

## Rebuild requirements

- Add one table/entity pair per canonical family or one generic table abstraction that can target the seeded schema.
- Keep table primary keys and field names identical to the seeded schema. Avoid adapter-specific renaming if possible.
- Record any Cake type-map overrides required for `decimal`, `date`, `datetime`, or booleans.
- If Cake needs custom schema metadata, note whether metadata caching is disabled for fairness or correctness.

## Things to document once rebuilt

- Whether typed entities preserve decimal precision or coerce to float.
- Whether date and datetime fields round-trip as Chronos objects or plain strings.
- Whether `saveMany()` or transactional batching is used anywhere, and if not, why not.
