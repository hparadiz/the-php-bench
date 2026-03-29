# PHPFUI Batch Adapter Notes

## Status

This adapter extends the base PHPFUI adapter and still assumes the removed legacy employee classes. It will need to be rebuilt after the base PHPFUI model layer is restored for the canonical families.

## Implementation details to preserve during the rebuild

- Inserts are buffered in memory and written in chunks of `200` rows during `flush()`.
- Deletes are also buffered and issued in `IN (...)` batches of `200`.
- Reads and updates still go through the base PHPFUI record path.

## Rebuild requirements

- Reuse the new PHPFUI canonical model classes instead of duplicating schema definitions.
- Keep chunking behavior explicit and documented because it can dominate write performance.
- If chunk size changes, record the reason here so benchmark comparisons remain interpretable.

## Things to document once rebuilt

- Whether batch insert uses one multi-row statement or repeated single-row statements under the hood.
- Whether batching changes transaction boundaries compared with base PHPFUI.
- How much warmup behavior differs from the non-batch adapter.
