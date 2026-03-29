<?php

namespace ThePHPBench\Propel2\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use ThePHPBench\Propel2\Canary;
use ThePHPBench\Propel2\CanaryQuery;


/**
 * This class defines the structure of the 'canaries' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class CanaryTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'ThePHPBench.Propel2.Map.CanaryTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'canaries';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'Canary';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\ThePHPBench\\Propel2\\Canary';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'ThePHPBench.Propel2.Canary';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 18;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 18;

    /**
     * the column name for the canary_id field
     */
    public const COL_CANARY_ID = 'canaries.canary_id';

    /**
     * the column name for the bool_flag field
     */
    public const COL_BOOL_FLAG = 'canaries.bool_flag';

    /**
     * the column name for the small_int field
     */
    public const COL_SMALL_INT = 'canaries.small_int';

    /**
     * the column name for the int_value field
     */
    public const COL_INT_VALUE = 'canaries.int_value';

    /**
     * the column name for the big_int field
     */
    public const COL_BIG_INT = 'canaries.big_int';

    /**
     * the column name for the float_value field
     */
    public const COL_FLOAT_VALUE = 'canaries.float_value';

    /**
     * the column name for the double_value field
     */
    public const COL_DOUBLE_VALUE = 'canaries.double_value';

    /**
     * the column name for the decimal_value field
     */
    public const COL_DECIMAL_VALUE = 'canaries.decimal_value';

    /**
     * the column name for the fixed_char_8 field
     */
    public const COL_FIXED_CHAR_8 = 'canaries.fixed_char_8';

    /**
     * the column name for the fixed_char_16 field
     */
    public const COL_FIXED_CHAR_16 = 'canaries.fixed_char_16';

    /**
     * the column name for the string_short field
     */
    public const COL_STRING_SHORT = 'canaries.string_short';

    /**
     * the column name for the string_medium field
     */
    public const COL_STRING_MEDIUM = 'canaries.string_medium';

    /**
     * the column name for the string_long field
     */
    public const COL_STRING_LONG = 'canaries.string_long';

    /**
     * the column name for the text_value field
     */
    public const COL_TEXT_VALUE = 'canaries.text_value';

    /**
     * the column name for the date_value field
     */
    public const COL_DATE_VALUE = 'canaries.date_value';

    /**
     * the column name for the datetime_value field
     */
    public const COL_DATETIME_VALUE = 'canaries.datetime_value';

    /**
     * the column name for the nullable_string field
     */
    public const COL_NULLABLE_STRING = 'canaries.nullable_string';

    /**
     * the column name for the nullable_int field
     */
    public const COL_NULLABLE_INT = 'canaries.nullable_int';

    /**
     * The default string format for model objects of the related table
     */
    public const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     *
     * @var array<string, mixed>
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME       => ['CanaryId', 'BoolFlag', 'SmallInt', 'IntValue', 'BigInt', 'FloatValue', 'DoubleValue', 'DecimalValue', 'FixedChar8', 'FixedChar16', 'StringShort', 'StringMedium', 'StringLong', 'TextValue', 'DateValue', 'DatetimeValue', 'NullableString', 'NullableInt', ],
        self::TYPE_CAMELNAME     => ['canaryId', 'boolFlag', 'smallInt', 'intValue', 'bigInt', 'floatValue', 'doubleValue', 'decimalValue', 'fixedChar8', 'fixedChar16', 'stringShort', 'stringMedium', 'stringLong', 'textValue', 'dateValue', 'datetimeValue', 'nullableString', 'nullableInt', ],
        self::TYPE_COLNAME       => [CanaryTableMap::COL_CANARY_ID, CanaryTableMap::COL_BOOL_FLAG, CanaryTableMap::COL_SMALL_INT, CanaryTableMap::COL_INT_VALUE, CanaryTableMap::COL_BIG_INT, CanaryTableMap::COL_FLOAT_VALUE, CanaryTableMap::COL_DOUBLE_VALUE, CanaryTableMap::COL_DECIMAL_VALUE, CanaryTableMap::COL_FIXED_CHAR_8, CanaryTableMap::COL_FIXED_CHAR_16, CanaryTableMap::COL_STRING_SHORT, CanaryTableMap::COL_STRING_MEDIUM, CanaryTableMap::COL_STRING_LONG, CanaryTableMap::COL_TEXT_VALUE, CanaryTableMap::COL_DATE_VALUE, CanaryTableMap::COL_DATETIME_VALUE, CanaryTableMap::COL_NULLABLE_STRING, CanaryTableMap::COL_NULLABLE_INT, ],
        self::TYPE_FIELDNAME     => ['canary_id', 'bool_flag', 'small_int', 'int_value', 'big_int', 'float_value', 'double_value', 'decimal_value', 'fixed_char_8', 'fixed_char_16', 'string_short', 'string_medium', 'string_long', 'text_value', 'date_value', 'datetime_value', 'nullable_string', 'nullable_int', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     *
     * @var array<string, mixed>
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME       => ['CanaryId' => 0, 'BoolFlag' => 1, 'SmallInt' => 2, 'IntValue' => 3, 'BigInt' => 4, 'FloatValue' => 5, 'DoubleValue' => 6, 'DecimalValue' => 7, 'FixedChar8' => 8, 'FixedChar16' => 9, 'StringShort' => 10, 'StringMedium' => 11, 'StringLong' => 12, 'TextValue' => 13, 'DateValue' => 14, 'DatetimeValue' => 15, 'NullableString' => 16, 'NullableInt' => 17, ],
        self::TYPE_CAMELNAME     => ['canaryId' => 0, 'boolFlag' => 1, 'smallInt' => 2, 'intValue' => 3, 'bigInt' => 4, 'floatValue' => 5, 'doubleValue' => 6, 'decimalValue' => 7, 'fixedChar8' => 8, 'fixedChar16' => 9, 'stringShort' => 10, 'stringMedium' => 11, 'stringLong' => 12, 'textValue' => 13, 'dateValue' => 14, 'datetimeValue' => 15, 'nullableString' => 16, 'nullableInt' => 17, ],
        self::TYPE_COLNAME       => [CanaryTableMap::COL_CANARY_ID => 0, CanaryTableMap::COL_BOOL_FLAG => 1, CanaryTableMap::COL_SMALL_INT => 2, CanaryTableMap::COL_INT_VALUE => 3, CanaryTableMap::COL_BIG_INT => 4, CanaryTableMap::COL_FLOAT_VALUE => 5, CanaryTableMap::COL_DOUBLE_VALUE => 6, CanaryTableMap::COL_DECIMAL_VALUE => 7, CanaryTableMap::COL_FIXED_CHAR_8 => 8, CanaryTableMap::COL_FIXED_CHAR_16 => 9, CanaryTableMap::COL_STRING_SHORT => 10, CanaryTableMap::COL_STRING_MEDIUM => 11, CanaryTableMap::COL_STRING_LONG => 12, CanaryTableMap::COL_TEXT_VALUE => 13, CanaryTableMap::COL_DATE_VALUE => 14, CanaryTableMap::COL_DATETIME_VALUE => 15, CanaryTableMap::COL_NULLABLE_STRING => 16, CanaryTableMap::COL_NULLABLE_INT => 17, ],
        self::TYPE_FIELDNAME     => ['canary_id' => 0, 'bool_flag' => 1, 'small_int' => 2, 'int_value' => 3, 'big_int' => 4, 'float_value' => 5, 'double_value' => 6, 'decimal_value' => 7, 'fixed_char_8' => 8, 'fixed_char_16' => 9, 'string_short' => 10, 'string_medium' => 11, 'string_long' => 12, 'text_value' => 13, 'date_value' => 14, 'datetime_value' => 15, 'nullable_string' => 16, 'nullable_int' => 17, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'CanaryId' => 'CANARY_ID',
        'Canary.CanaryId' => 'CANARY_ID',
        'canaryId' => 'CANARY_ID',
        'canary.canaryId' => 'CANARY_ID',
        'CanaryTableMap::COL_CANARY_ID' => 'CANARY_ID',
        'COL_CANARY_ID' => 'CANARY_ID',
        'canary_id' => 'CANARY_ID',
        'canaries.canary_id' => 'CANARY_ID',
        'BoolFlag' => 'BOOL_FLAG',
        'Canary.BoolFlag' => 'BOOL_FLAG',
        'boolFlag' => 'BOOL_FLAG',
        'canary.boolFlag' => 'BOOL_FLAG',
        'CanaryTableMap::COL_BOOL_FLAG' => 'BOOL_FLAG',
        'COL_BOOL_FLAG' => 'BOOL_FLAG',
        'bool_flag' => 'BOOL_FLAG',
        'canaries.bool_flag' => 'BOOL_FLAG',
        'SmallInt' => 'SMALL_INT',
        'Canary.SmallInt' => 'SMALL_INT',
        'smallInt' => 'SMALL_INT',
        'canary.smallInt' => 'SMALL_INT',
        'CanaryTableMap::COL_SMALL_INT' => 'SMALL_INT',
        'COL_SMALL_INT' => 'SMALL_INT',
        'small_int' => 'SMALL_INT',
        'canaries.small_int' => 'SMALL_INT',
        'IntValue' => 'INT_VALUE',
        'Canary.IntValue' => 'INT_VALUE',
        'intValue' => 'INT_VALUE',
        'canary.intValue' => 'INT_VALUE',
        'CanaryTableMap::COL_INT_VALUE' => 'INT_VALUE',
        'COL_INT_VALUE' => 'INT_VALUE',
        'int_value' => 'INT_VALUE',
        'canaries.int_value' => 'INT_VALUE',
        'BigInt' => 'BIG_INT',
        'Canary.BigInt' => 'BIG_INT',
        'bigInt' => 'BIG_INT',
        'canary.bigInt' => 'BIG_INT',
        'CanaryTableMap::COL_BIG_INT' => 'BIG_INT',
        'COL_BIG_INT' => 'BIG_INT',
        'big_int' => 'BIG_INT',
        'canaries.big_int' => 'BIG_INT',
        'FloatValue' => 'FLOAT_VALUE',
        'Canary.FloatValue' => 'FLOAT_VALUE',
        'floatValue' => 'FLOAT_VALUE',
        'canary.floatValue' => 'FLOAT_VALUE',
        'CanaryTableMap::COL_FLOAT_VALUE' => 'FLOAT_VALUE',
        'COL_FLOAT_VALUE' => 'FLOAT_VALUE',
        'float_value' => 'FLOAT_VALUE',
        'canaries.float_value' => 'FLOAT_VALUE',
        'DoubleValue' => 'DOUBLE_VALUE',
        'Canary.DoubleValue' => 'DOUBLE_VALUE',
        'doubleValue' => 'DOUBLE_VALUE',
        'canary.doubleValue' => 'DOUBLE_VALUE',
        'CanaryTableMap::COL_DOUBLE_VALUE' => 'DOUBLE_VALUE',
        'COL_DOUBLE_VALUE' => 'DOUBLE_VALUE',
        'double_value' => 'DOUBLE_VALUE',
        'canaries.double_value' => 'DOUBLE_VALUE',
        'DecimalValue' => 'DECIMAL_VALUE',
        'Canary.DecimalValue' => 'DECIMAL_VALUE',
        'decimalValue' => 'DECIMAL_VALUE',
        'canary.decimalValue' => 'DECIMAL_VALUE',
        'CanaryTableMap::COL_DECIMAL_VALUE' => 'DECIMAL_VALUE',
        'COL_DECIMAL_VALUE' => 'DECIMAL_VALUE',
        'decimal_value' => 'DECIMAL_VALUE',
        'canaries.decimal_value' => 'DECIMAL_VALUE',
        'FixedChar8' => 'FIXED_CHAR_8',
        'Canary.FixedChar8' => 'FIXED_CHAR_8',
        'fixedChar8' => 'FIXED_CHAR_8',
        'canary.fixedChar8' => 'FIXED_CHAR_8',
        'CanaryTableMap::COL_FIXED_CHAR_8' => 'FIXED_CHAR_8',
        'COL_FIXED_CHAR_8' => 'FIXED_CHAR_8',
        'fixed_char_8' => 'FIXED_CHAR_8',
        'canaries.fixed_char_8' => 'FIXED_CHAR_8',
        'FixedChar16' => 'FIXED_CHAR_16',
        'Canary.FixedChar16' => 'FIXED_CHAR_16',
        'fixedChar16' => 'FIXED_CHAR_16',
        'canary.fixedChar16' => 'FIXED_CHAR_16',
        'CanaryTableMap::COL_FIXED_CHAR_16' => 'FIXED_CHAR_16',
        'COL_FIXED_CHAR_16' => 'FIXED_CHAR_16',
        'fixed_char_16' => 'FIXED_CHAR_16',
        'canaries.fixed_char_16' => 'FIXED_CHAR_16',
        'StringShort' => 'STRING_SHORT',
        'Canary.StringShort' => 'STRING_SHORT',
        'stringShort' => 'STRING_SHORT',
        'canary.stringShort' => 'STRING_SHORT',
        'CanaryTableMap::COL_STRING_SHORT' => 'STRING_SHORT',
        'COL_STRING_SHORT' => 'STRING_SHORT',
        'string_short' => 'STRING_SHORT',
        'canaries.string_short' => 'STRING_SHORT',
        'StringMedium' => 'STRING_MEDIUM',
        'Canary.StringMedium' => 'STRING_MEDIUM',
        'stringMedium' => 'STRING_MEDIUM',
        'canary.stringMedium' => 'STRING_MEDIUM',
        'CanaryTableMap::COL_STRING_MEDIUM' => 'STRING_MEDIUM',
        'COL_STRING_MEDIUM' => 'STRING_MEDIUM',
        'string_medium' => 'STRING_MEDIUM',
        'canaries.string_medium' => 'STRING_MEDIUM',
        'StringLong' => 'STRING_LONG',
        'Canary.StringLong' => 'STRING_LONG',
        'stringLong' => 'STRING_LONG',
        'canary.stringLong' => 'STRING_LONG',
        'CanaryTableMap::COL_STRING_LONG' => 'STRING_LONG',
        'COL_STRING_LONG' => 'STRING_LONG',
        'string_long' => 'STRING_LONG',
        'canaries.string_long' => 'STRING_LONG',
        'TextValue' => 'TEXT_VALUE',
        'Canary.TextValue' => 'TEXT_VALUE',
        'textValue' => 'TEXT_VALUE',
        'canary.textValue' => 'TEXT_VALUE',
        'CanaryTableMap::COL_TEXT_VALUE' => 'TEXT_VALUE',
        'COL_TEXT_VALUE' => 'TEXT_VALUE',
        'text_value' => 'TEXT_VALUE',
        'canaries.text_value' => 'TEXT_VALUE',
        'DateValue' => 'DATE_VALUE',
        'Canary.DateValue' => 'DATE_VALUE',
        'dateValue' => 'DATE_VALUE',
        'canary.dateValue' => 'DATE_VALUE',
        'CanaryTableMap::COL_DATE_VALUE' => 'DATE_VALUE',
        'COL_DATE_VALUE' => 'DATE_VALUE',
        'date_value' => 'DATE_VALUE',
        'canaries.date_value' => 'DATE_VALUE',
        'DatetimeValue' => 'DATETIME_VALUE',
        'Canary.DatetimeValue' => 'DATETIME_VALUE',
        'datetimeValue' => 'DATETIME_VALUE',
        'canary.datetimeValue' => 'DATETIME_VALUE',
        'CanaryTableMap::COL_DATETIME_VALUE' => 'DATETIME_VALUE',
        'COL_DATETIME_VALUE' => 'DATETIME_VALUE',
        'datetime_value' => 'DATETIME_VALUE',
        'canaries.datetime_value' => 'DATETIME_VALUE',
        'NullableString' => 'NULLABLE_STRING',
        'Canary.NullableString' => 'NULLABLE_STRING',
        'nullableString' => 'NULLABLE_STRING',
        'canary.nullableString' => 'NULLABLE_STRING',
        'CanaryTableMap::COL_NULLABLE_STRING' => 'NULLABLE_STRING',
        'COL_NULLABLE_STRING' => 'NULLABLE_STRING',
        'nullable_string' => 'NULLABLE_STRING',
        'canaries.nullable_string' => 'NULLABLE_STRING',
        'NullableInt' => 'NULLABLE_INT',
        'Canary.NullableInt' => 'NULLABLE_INT',
        'nullableInt' => 'NULLABLE_INT',
        'canary.nullableInt' => 'NULLABLE_INT',
        'CanaryTableMap::COL_NULLABLE_INT' => 'NULLABLE_INT',
        'COL_NULLABLE_INT' => 'NULLABLE_INT',
        'nullable_int' => 'NULLABLE_INT',
        'canaries.nullable_int' => 'NULLABLE_INT',
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function initialize(): void
    {
        // attributes
        $this->setName('canaries');
        $this->setPhpName('Canary');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ThePHPBench\\Propel2\\Canary');
        $this->setPackage('ThePHPBench.Propel2');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('canary_id', 'CanaryId', 'INTEGER', true, null, null);
        $this->addColumn('bool_flag', 'BoolFlag', 'BOOLEAN', true, null, null);
        $this->addColumn('small_int', 'SmallInt', 'SMALLINT', true, null, null);
        $this->addColumn('int_value', 'IntValue', 'INTEGER', true, null, null);
        $this->addColumn('big_int', 'BigInt', 'BIGINT', true, null, null);
        $this->addColumn('float_value', 'FloatValue', 'FLOAT', true, null, null);
        $this->addColumn('double_value', 'DoubleValue', 'DOUBLE', true, null, null);
        $this->addColumn('decimal_value', 'DecimalValue', 'DECIMAL', true, 12, null);
        $this->addColumn('fixed_char_8', 'FixedChar8', 'CHAR', true, 8, null);
        $this->addColumn('fixed_char_16', 'FixedChar16', 'CHAR', true, 16, null);
        $this->addColumn('string_short', 'StringShort', 'VARCHAR', true, 32, null);
        $this->addColumn('string_medium', 'StringMedium', 'VARCHAR', true, 128, null);
        $this->addColumn('string_long', 'StringLong', 'VARCHAR', true, 512, null);
        $this->addColumn('text_value', 'TextValue', 'LONGVARCHAR', true, null, null);
        $this->addColumn('date_value', 'DateValue', 'DATE', true, null, null);
        $this->addColumn('datetime_value', 'DatetimeValue', 'TIMESTAMP', false, null, null);
        $this->addColumn('nullable_string', 'NullableString', 'VARCHAR', false, 64, null);
        $this->addColumn('nullable_int', 'NullableInt', 'INTEGER', false, null, null);
    }

    /**
     * Build the RelationMap objects for this table relationships
     *
     * @return void
     */
    public function buildRelations(): void
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string|null The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): ?string
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array $row Resultset row.
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param bool $withPrefix Whether to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass(bool $withPrefix = true): string
    {
        return $withPrefix ? CanaryTableMap::CLASS_DEFAULT : CanaryTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array $row Row returned by DataFetcher->fetch().
     * @param int $offset The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array (Canary object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = CanaryTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CanaryTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CanaryTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CanaryTableMap::OM_CLASS;
            /** @var Canary $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CanaryTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array<object>
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher): array
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = CanaryTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CanaryTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Canary $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CanaryTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria Object containing the columns to add.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function addSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->addSelectColumn(CanaryTableMap::COL_CANARY_ID);
            $criteria->addSelectColumn(CanaryTableMap::COL_BOOL_FLAG);
            $criteria->addSelectColumn(CanaryTableMap::COL_SMALL_INT);
            $criteria->addSelectColumn(CanaryTableMap::COL_INT_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_BIG_INT);
            $criteria->addSelectColumn(CanaryTableMap::COL_FLOAT_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_DOUBLE_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_DECIMAL_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_FIXED_CHAR_8);
            $criteria->addSelectColumn(CanaryTableMap::COL_FIXED_CHAR_16);
            $criteria->addSelectColumn(CanaryTableMap::COL_STRING_SHORT);
            $criteria->addSelectColumn(CanaryTableMap::COL_STRING_MEDIUM);
            $criteria->addSelectColumn(CanaryTableMap::COL_STRING_LONG);
            $criteria->addSelectColumn(CanaryTableMap::COL_TEXT_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_DATE_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_DATETIME_VALUE);
            $criteria->addSelectColumn(CanaryTableMap::COL_NULLABLE_STRING);
            $criteria->addSelectColumn(CanaryTableMap::COL_NULLABLE_INT);
        } else {
            $criteria->addSelectColumn($alias . '.canary_id');
            $criteria->addSelectColumn($alias . '.bool_flag');
            $criteria->addSelectColumn($alias . '.small_int');
            $criteria->addSelectColumn($alias . '.int_value');
            $criteria->addSelectColumn($alias . '.big_int');
            $criteria->addSelectColumn($alias . '.float_value');
            $criteria->addSelectColumn($alias . '.double_value');
            $criteria->addSelectColumn($alias . '.decimal_value');
            $criteria->addSelectColumn($alias . '.fixed_char_8');
            $criteria->addSelectColumn($alias . '.fixed_char_16');
            $criteria->addSelectColumn($alias . '.string_short');
            $criteria->addSelectColumn($alias . '.string_medium');
            $criteria->addSelectColumn($alias . '.string_long');
            $criteria->addSelectColumn($alias . '.text_value');
            $criteria->addSelectColumn($alias . '.date_value');
            $criteria->addSelectColumn($alias . '.datetime_value');
            $criteria->addSelectColumn($alias . '.nullable_string');
            $criteria->addSelectColumn($alias . '.nullable_int');
        }
    }

    /**
     * Remove all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be removed as they are only loaded on demand.
     *
     * @param Criteria $criteria Object containing the columns to remove.
     * @param string|null $alias Optional table alias
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return void
     */
    public static function removeSelectColumns(Criteria $criteria, ?string $alias = null): void
    {
        if (null === $alias) {
            $criteria->removeSelectColumn(CanaryTableMap::COL_CANARY_ID);
            $criteria->removeSelectColumn(CanaryTableMap::COL_BOOL_FLAG);
            $criteria->removeSelectColumn(CanaryTableMap::COL_SMALL_INT);
            $criteria->removeSelectColumn(CanaryTableMap::COL_INT_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_BIG_INT);
            $criteria->removeSelectColumn(CanaryTableMap::COL_FLOAT_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_DOUBLE_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_DECIMAL_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_FIXED_CHAR_8);
            $criteria->removeSelectColumn(CanaryTableMap::COL_FIXED_CHAR_16);
            $criteria->removeSelectColumn(CanaryTableMap::COL_STRING_SHORT);
            $criteria->removeSelectColumn(CanaryTableMap::COL_STRING_MEDIUM);
            $criteria->removeSelectColumn(CanaryTableMap::COL_STRING_LONG);
            $criteria->removeSelectColumn(CanaryTableMap::COL_TEXT_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_DATE_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_DATETIME_VALUE);
            $criteria->removeSelectColumn(CanaryTableMap::COL_NULLABLE_STRING);
            $criteria->removeSelectColumn(CanaryTableMap::COL_NULLABLE_INT);
        } else {
            $criteria->removeSelectColumn($alias . '.canary_id');
            $criteria->removeSelectColumn($alias . '.bool_flag');
            $criteria->removeSelectColumn($alias . '.small_int');
            $criteria->removeSelectColumn($alias . '.int_value');
            $criteria->removeSelectColumn($alias . '.big_int');
            $criteria->removeSelectColumn($alias . '.float_value');
            $criteria->removeSelectColumn($alias . '.double_value');
            $criteria->removeSelectColumn($alias . '.decimal_value');
            $criteria->removeSelectColumn($alias . '.fixed_char_8');
            $criteria->removeSelectColumn($alias . '.fixed_char_16');
            $criteria->removeSelectColumn($alias . '.string_short');
            $criteria->removeSelectColumn($alias . '.string_medium');
            $criteria->removeSelectColumn($alias . '.string_long');
            $criteria->removeSelectColumn($alias . '.text_value');
            $criteria->removeSelectColumn($alias . '.date_value');
            $criteria->removeSelectColumn($alias . '.datetime_value');
            $criteria->removeSelectColumn($alias . '.nullable_string');
            $criteria->removeSelectColumn($alias . '.nullable_int');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap(): TableMap
    {
        return Propel::getServiceContainer()->getDatabaseMap(CanaryTableMap::DATABASE_NAME)->getTable(CanaryTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a Canary or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or Canary object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ?ConnectionInterface $con = null): int
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ThePHPBench\Propel2\Canary) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CanaryTableMap::DATABASE_NAME);
            $criteria->add(CanaryTableMap::COL_CANARY_ID, (array) $values, Criteria::IN);
        }

        $query = CanaryQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CanaryTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CanaryTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the canaries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return CanaryQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Canary or Criteria object.
     *
     * @param mixed $criteria Criteria or Canary object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Canary object
        }

        if ($criteria->containsKey(CanaryTableMap::COL_CANARY_ID) && $criteria->keyContainsValue(CanaryTableMap::COL_CANARY_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CanaryTableMap::COL_CANARY_ID.')');
        }


        // Set the correct dbName
        $query = CanaryQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
