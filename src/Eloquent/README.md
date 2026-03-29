# Eloquent Adapter Notes

## Status

This adapter still targets the removed `Employee` model. [Tests.php](/home/akujin/Divergence/php-orm-sql-benchmarks/src/Eloquent/Tests.php) references `\ThePHPBench\Eloquent\Model\Employee`, so the model layer still needs to be rebuilt.

## Implementation details to preserve during the rebuild

- Eloquent opens one transaction per phase and commits in `flush()`.
- Deletes are deferred and batched with `Employee::destroy($this->deletes)`.
- Reads use `Model::find($id)`.
- The adapter boots a full Capsule manager, event dispatcher, and global Eloquent state during `init()`.

## Rebuild requirements

- Add one model per canonical family under `src/Eloquent/Model`.
- Define `$table`, primary key name, incrementing strategy, and casts explicitly instead of relying on conventions.
- Keep the seeded field names unchanged.
- Document any casts used for booleans, dates, datetimes, and decimals because they materially affect hydration cost and value semantics.

## Things to document once rebuilt

- Whether `decimal` uses Eloquent string casting or float casting.
- Whether `date` and `datetime` come back as Carbon instances or raw values.
- Whether mass-assignment helpers, `insert()`, or upsert APIs are used anywhere instead of per-row `save()`.
