# RedBean Adapter Notes

## Status

This adapter is restored as part of the canonical-model rebuild, but it remains a deliberate special case.

## Implementation details

- RedBean does not use generated model classes in this repo. It operates on dynamic beans.
- The benchmark uses the canonical `StringVariable` field set, but RedBean keeps its conventional `id` primary key and a RedBean-safe bean/table name: `stringvariablerecord`.
- That primary-key exception is isolated in the adapter's schema bootstrap so the rest of the benchmark can stay on the canonical field names.

## Why this is different

- RedBean's default bean lifecycle is tightly coupled to an `id` primary key.
- Forcing named primary keys into the adapter would change the benchmark from "RedBean as shipped" into a custom compatibility layer.
- This difference should stay documented anywhere RedBean results are compared against the stricter model-mapped ORMs.
