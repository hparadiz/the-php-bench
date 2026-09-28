# ActiveRecord Adapter Notes

## Status

This adapter has not been rebuilt for the new benchmark model families yet. The current test harness in [Tests.php](Tests.php) still expects `\ThePHPBench\ActiveRecord\Model\Employee`, which was intentionally removed during the legacy cleanup.

## Implementation details to preserve during the rebuild

- The adapter batches work inside one open transaction per benchmark phase. `insert()`, `update()`, and `delete()` all call `beginTransaction()`, while `flush()` commits.
- Deletes are deferred. `delete()` only records ids, and `flush()` issues one `delete_all()` using `where(['employee_id' => $this->deletes])`.
- Reads are primary-key lookups with `Model::find($id)`.
- The adapter uses php-activerecord connection strings directly, so backend capability is defined by that library rather than by this repo.

## Rebuild requirements

- Add one model class per canonical family: `Canary`, `IntFixed`, `FloatFixed`, `StringFixed`, `StringVariable`.
- Keep schema field names aligned with [ScenarioRegistry.php](../Seed/ScenarioRegistry.php).
- Document any type coercions here. If the library lacks a first-class mapping for booleans, decimals, dates, or text/blob-like fields, note the fallback type and its expected storage behavior.
- Decide whether transaction batching remains fair for this adapter. If retained, keep that choice documented because it materially changes write performance.

## Things to document once rebuilt

- Whether fixed-width `char` columns stay fixed-width or are surfaced as generic strings.
- Whether decimal fields round-trip as strings, floats, or custom numeric wrappers.
- Whether bulk delete remains batched while insert/update stay row-at-a-time.
