# DivergenceV3 Model Notes

## Status

This is the second adapter rebuilt for the new benchmark families. The current model classes live in [Model](Model), and [ModelRegistry.php](ModelRegistry.php) maps canonical family ids to those classes.

## Implemented model families

- `Canary`
- `IntFixed`
- `FloatFixed`
- `StringFixed`
- `StringVariable`

## Implementation details

- The models use `DivergenceReflections\Models\ActiveRecord` plus attribute-based `#[Column(...)]` metadata.
- Index definitions are declared per model in `public static $indexes`.
- `StringVariable` is aligned to the canonical seeded schema and is the first active scenario wired into [Tests.php](Tests.php).
- The current harness verifies updates against the `title` field rather than the old `Employee.last_name` field.

## Known type notes

- `double` is currently mapped with the same Divergence column type used for `float`. If Divergence V3 gains a distinct double mapping, record the change here before using it.
- Large text uses `clob`.
- `datetime` uses Divergence `timestamp`.
- As with V2, there is no canonical array-of-strings benchmark field yet; if added, document whether V3 exposes a native collection type or needs serialization.

## Remaining work

- Swap the remaining northwind-era schema bootstrap out for the seeder-driven schema path.
- Generalize the harness so scenario family, row count, and indexed mode come from the benchmark configuration instead of the adapter hardcoding `StringVariable`.
