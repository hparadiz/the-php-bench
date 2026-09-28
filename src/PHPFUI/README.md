# PHPFUI Adapter Notes

## Status

This adapter still points at the removed `Record\Employee` and `Table\Employee` classes from [Tests.php](Tests.php). It needs a fresh model layer for the canonical families.

## Implementation details to preserve during the rebuild

- This adapter performs row-at-a-time inserts, reads, updates, and deletes with PHPFUI record objects.
- It sets the ORM namespace roots dynamically during bootstrap, so generated record/table classes can still live under this framework directory.
- There is no phase-level transaction batching in the base PHPFUI adapter today.

## Rebuild requirements

- Generate or hand-author record/table classes for `Canary`, `IntFixed`, `FloatFixed`, `StringFixed`, and `StringVariable`.
- Keep canonical field names and primary keys aligned with the seeded schema.
- Document any PHPFUI-specific type limitations, especially around decimal precision, dates, datetimes, and large text.

## Things to document once rebuilt

- Whether PHPFUI distinguishes `char` from `varchar` in generated metadata.
- Whether decimals are treated as strings or floats.
- Whether record hydration incurs extra per-field conversion work compared with the batch adapter.
