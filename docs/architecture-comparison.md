# PHP ORM Architecture Comparison

This document provides a detailed architectural breakdown of every ORM included in the benchmark suite, along with notes on how each implementation maps to the benchmark test harness.

---

## Architectural Patterns

Five distinct patterns are represented across the benchmarked ORMs:

| Pattern | ORMs |
|---|---|
| **ActiveRecord** | php-activerecord, Eloquent, DivergenceV2, DivergenceV3, RedBean |
| **Data Mapper** | Doctrine |
| **Table + Entity split** | CakePHP, PHPFUI |
| **Schema-less / dynamic** | RedBean |
| **Code-generated** | Propel2 |

---

## ORM Profiles

### RedBean

- **Pattern:** Schema-less ActiveRecord / thin PDO wrapper
- **Mapping:** None — tables are introspected at runtime; rows are returned as generic `OODBBean` (array wrapper) instances
- **Model class:** Empty `SimpleModel` shell; no field declarations, no attributes, no annotations
- **API:** Procedural facade — `R::dispense()`, `R::load()`, `R::store()`, `R::trash()`
- **Schema:** Uses `schema-pkid.sql` — every table PK is `id` (RedBean convention), *not* the domain-named key (`employee_id`) used by all other ORMs
- **Identity map:** None
- **Relationship tracking:** None
- **Type coercion:** None
- **Supported backends:** SQLite, MySQL/MariaDB
- **Benchmark notes:** RedBean's numbers represent a near-raw-PDO floor rather than a true ORM comparison. It skips every cost that other ORMs pay: hydration, identity mapping, type casting, relationship tracking. Its `schema-pkid.sql` also omits all foreign key constraints and indexes present in the standard schema.

---

### php-activerecord

- **Pattern:** ActiveRecord
- **Mapping:** Static class properties — `$primary_key`, `$table_name`; column metadata via `@property` PHPDoc annotations (no PHP 8 attributes)
- **API:** `find()`, `save()`, `delete()`
- **Schema:** Standard `schema.sql` with `employee_id` PK
- **Identity map:** Yes (per-request static cache)
- **Supported backends:** SQLite, MySQL *(MariaDB blocked — `SET @OLD_UNIQUE_CHECKS` syntax in the schema is incompatible with its schema loader)*
- **Benchmark notes:** Performs well on SQLite::memory: — competitive with Eloquent. The MariaDB variant is disabled in `config/config.php`.

---

### CakePHP ORM

- **Pattern:** Table + Entity (two-layer)
- **Mapping:** `Table` class handles queries, relationships and persistence; `Record`/Entity classes are plain value objects with typed properties
- **API:** `Table::get()`, `Table::save()`, `Table::delete()`; entities are returned from table operations
- **Schema:** Standard `schema.sql`
- **Identity map:** No persistent identity map across requests; entities are re-hydrated per query
- **Event system:** Full lifecycle hooks (beforeSave, afterSave, beforeFind, etc.)
- **Associations:** hasOne, hasMany, belongsTo, belongsToMany
- **Supported backends:** SQLite, MySQL/MariaDB
- **Benchmark notes:** The heaviest hydration overhead of all tested ORMs. Every read produces a full entity graph walk even for single-column access. Read and update times are consistently the highest among standard ORMs.

---

### CakeCached

- **Pattern:** Table + Entity (identical to CakePHP)
- **Notes:** Extends the CakePHP ORM test suite with a presumed caching layer. In the current benchmark pattern (sequential inserts, reads, updates, and deletes with unique records), the cache does not produce meaningful improvements. Results are statistically identical to plain Cake. A cache warms best under repeated reads of the same records, which this benchmark does not exercise.

---

### Doctrine ORM

- **Pattern:** Data Mapper
- **Mapping:** PHP 8 `#[Attribute]` annotations on Entity classes (`#[Entity]`, `#[Column]`, `#[Id]`, `#[GeneratedValue]`)
- **API:** `EntityManager::persist()`, `EntityManager::flush()`, `EntityManager::find()`, `EntityManager::remove()`
- **Unit of Work:** Write-behind — changes are tracked in memory and committed as a batch on `flush()`. In this benchmark `flush()` is called after every single insert/update/delete, which is the worst-case usage pattern for Doctrine
- **Proxy classes:** Auto-generated for lazy-loading relationships; generated into `src/Doctrine/Proxy/` at runtime
- **Caches:** Metadata and query result caches use `ArrayAdapter` (in-memory per request)
- **Supported backends:** SQLite, MySQL/MariaDB
- **Benchmark notes:** The `flush()`-per-operation pattern incurs the full Unit of Work commit cycle on every row. In a real application, batching persists before a single flush would reduce this to near-zero overhead. Doctrine is architecturally the most powerful ORM in the suite, but its benchmark numbers reflect worst-case usage, not representative production performance.

---

### Laravel Eloquent

- **Pattern:** ActiveRecord
- **Mapping:** Class properties — `$table`, `$primaryKey`, `$timestamps = false`; columns accessed via magic `__get`/`__set` through the model's attribute bag
- **API:** `Model::find()`, `$model->save()`, `Model::destroy()`
- **Schema:** Standard `schema.sql`
- **Identity map:** None (models are independent instances)
- **Supported backends:** SQLite, MySQL/MariaDB *(SQLite file backend requires an absolute path — patched in `src/Eloquent/Tests.php`)*
- **Capsule:** Uses `Illuminate\Database\Capsule\Manager` for standalone setup outside Laravel
- **Benchmark notes:** Middle-of-pack performance. The SQLite file variant is notably slower than memory due to disk I/O on every `save()` call with no write batching. MariaDB performance is reasonable. The absolute-path SQLite bug was a regression introduced by Eloquent's connector enforcing absolute paths in newer versions.

---

### PHPFUI ORM

- **Pattern:** Table + Record (two-layer)
- **Mapping:** `Record` class represents a single row; `Table` class represents the full table for collection queries. PK convention uses `$idSuffix = '_id'` which maps to `employee_id`
- **API:** `new Record($id)`, `$record->insert()`, `$record->update()`, `$record->delete()`; `Record::loaded()` checks hydration success
- **Schema:** Standard `schema.sql`
- **Supported backends:** SQLite, MySQL/MariaDB
- **Benchmark notes:** Lightest overhead of the two-layer ORMs. The explicit `insert()` vs `update()` method split (no unified `save()`) avoids an existence check on every write.

---

### PHPFUI ORM (Batch)

- **Pattern:** Table + Record with bulk operation buffering
- **Extends:** `ThePHPBench\PHPFUI\Tests` — inherits all read/update logic
- **Inserts:** Buffered into an array; flushed as a single bulk `INSERT` via `Table::insert(array)`
- **Deletes:** Buffered into an array; flushed as a single `DELETE WHERE employee_id IN (...)` via `Table::delete()`
- **Flush trigger:** Called by the `TestRunner` between test phases
- **Benchmark notes:** Demonstrates that bulk operations fundamentally change the performance profile. The batch insert eliminates per-row round-trip overhead. This is a legitimate optimization pattern, not a cheat — the data must all be correct and present at the end of the phase.

---

### Propel2

- **Pattern:** Code-generated ActiveRecord + Query Objects
- **Mapping:** All model classes, query classes, base classes and table map classes are pre-generated PHP from a schema XML definition. No runtime reflection
- **API:** `$employee->save()`, `$employee->delete()`, `EmployeeQuery::create()->findPk($id)`
- **Code generation:** `~30 files` generated per schema — Base classes, concrete classes, Query objects, Map classes
- **Supported backends:** SQLite, MySQL/MariaDB
- **Benchmark notes:** Not yet run in the current benchmark environment. Propel2's generated code approach means zero runtime reflection overhead, which historically produces competitive read performance.

---

### DivergenceV2

- **Pattern:** ActiveRecord
- **Mapping:** PHP 8 `#[Column]` attributes directly on protected class properties; `$tableName`, `$primaryKey`, `$singularNoun`, `$pluralNoun` static class properties
- **API:** `ActiveRecord::getByID()`, `$record->save()`, `ActiveRecord::delete()`
- **Traits:** Uses `Getters` trait for property access
- **Supported backends:** MySQL/MariaDB only — `dbSupported()` returns `false` for all other drivers
- **Bootstrap:** Requires a minimal `App` instance; the benchmark injects DB config via Reflection to bypass the file-based config system
- **Models:** Only `Employee.php` is defined — matching the benchmark's actual usage. Other ORMs carry full Northwind model sets from code generation; DivergenceV2 only defines what it uses
- **Benchmark notes:** Competitive on MariaDB for read and random-read operations. Write operations (insert/update/delete) are slower than Eloquent on the same backend, suggesting overhead in the `save()` path — likely attribute parsing, dirty-tracking, or the reflection-based column map rebuild. No SQLite support limits cross-backend comparison.

### DivergenceV3

- **Pattern:** ActiveRecord
- **Mapping:** PHP 8 `#[Column]` attributes directly on protected class properties; `$tableName`, `$primaryKey`, `$singularNoun`, `$pluralNoun` static class properties
- **API:** `ActiveRecord::getByID()`, `$record->save()`, `ActiveRecord::delete()`
- **Traits:** Uses `Getters` trait for property access
- **Supported backends:** SQLite, MySQL/MariaDB
- **Bootstrap:** Requires a minimal `App` instance; the benchmark injects DB config via Reflection to bypass the file-based config system
- **Models:** Only `Employee.php` is defined in the adapter layer, mirroring the benchmark's actual usage
- **Benchmark notes:** Extends the Divergence line with SQLite support, which makes it comparable across the full benchmark matrix instead of only within MySQL/MariaDB runs.

---

## Key Benchmark Caveats

### RedBean is not an apples-to-apples comparison
RedBean's schema uses `id` instead of named PKs, has no model mapping layer, and skips constraint enforcement. Its numbers represent the minimum achievable time for a PDO insert/read/update/delete loop on this hardware. That makes it a floor reference, not a directly comparable ORM result.

### Doctrine's numbers reflect worst-case usage
Calling `flush()` after every single `persist()` forces a full Unit of Work cycle per row. Production Doctrine code batches many `persist()` calls before a single `flush()`, which would reduce insert/update times by an order of magnitude.

### PHPFUIBatch shows what bulk operations change
The difference between PHPFUI and PHPFUIBatch isolates the cost of per-row round-trips. This is the most informative comparison in the suite for understanding ORM write overhead.

### CakeCached is nearly identical to Cake in this pattern
Cache layers benefit repeated reads of the same data. This benchmark reads each record at most twice (read phase + update-verify phase). A cache does not help sequential unique-key access.

### DivergenceV2 is MySQL/MariaDB only
All cross-backend comparisons exclude DivergenceV2. Its relative performance within MariaDB is valid and informative, but it cannot be ranked against ORMs with SQLite results.

---

## Feature Matrix

| Feature | RedBean | php-activerecord | CakePHP | Doctrine | Eloquent | PHPFUI | Propel2 | DivergenceV2 | DivergenceV3 |
|---|---|---|---|---|---|---|---|---|---|
| PHP 8 Attributes | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ | ✗ | ✓ | ✓ |
| PHPDoc annotations | ✗ | ✓ | ✗ | ✗ | ✓ | ✓ | ✓ | ✗ | ✗ |
| Schema-less / dynamic | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ |
| Code generation | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ |
| Unit of Work / write-behind | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ |
| Bulk insert API | ✗ | ✗ | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ | ✗ |
| Identity map | ✗ | ✓ | ✗ | ✓ | ✗ | ✗ | ✓ | ✗ | ✗ |
| Lazy loading | ✗ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ | ✗ | ✗ |
| Lifecycle events/hooks | ✗ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| Relationship mapping | ✓* | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| SQLite support | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ |
| MySQL/MariaDB support | ✓ | ✓† | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| PostgreSQL support | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |
| Standalone (no framework) | ✓ | ✓ | ✓ | ✓ | ✓† | ✓ | ✓ | ✓† | ✓† |

\* RedBean supports dynamic relationships via `R::related()` but without explicit schema definition  
† Requires shim/workaround for standalone use
