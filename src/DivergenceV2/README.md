# DivergenceV2 Model Notes

## Status

This is the first adapter rebuilt for the new benchmark families. The current model classes live in [Model](Model), and [ModelRegistry.php](ModelRegistry.php) maps canonical family ids to those classes.

## Implemented model families

- `Canary`
- `IntFixed`
- `FloatFixed`
- `StringFixed`
- `StringVariable`

## Implementation details

- The models use Divergence V2 attribute-based `#[Column(...)]` mapping on top of `Divergence\Models\ActiveRecord`.
- Index definitions are declared in each model via `public static $indexes` so the indexed and unindexed scenarios can be derived from one canonical definition.
- `StringVariable` now matches the new canonical seeded schema: `name`, `title`, `email`, `company`, `city`, `region`, `postal_code`, `country`, `phone`, `notes`.
- The current benchmark harness in [Tests.php](Tests.php) uses `StringVariable` as the first converted scenario and verifies updates against the `title` field.

## Known type notes

- `double` fields are currently represented with Divergence's `float` column type because that is the closest available mapping in this adapter layer.
- `text` uses Divergence `clob`.
- `datetime` uses Divergence `timestamp`.
- There is no array-of-strings field in the canonical benchmark families today. If one is added later, document whether Divergence stores it natively or via serialization.

## Remaining work

- Replace the legacy schema-loader path in [src/Test.php](../Test.php) with seeded scenario application so these models can run against real generated tables.
- Rebuild the benchmark harness to select model family and index mode dynamically instead of hardcoding `StringVariable`.
