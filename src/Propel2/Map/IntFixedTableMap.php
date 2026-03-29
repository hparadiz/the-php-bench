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
use ThePHPBench\Propel2\IntFixed;
use ThePHPBench\Propel2\IntFixedQuery;


/**
 * This class defines the structure of the 'int_fixed_records' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class IntFixedTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'ThePHPBench.Propel2.Map.IntFixedTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'int_fixed_records';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'IntFixed';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\ThePHPBench\\Propel2\\IntFixed';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'ThePHPBench.Propel2.IntFixed';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 13;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 13;

    /**
     * the column name for the int_fixed_id field
     */
    public const COL_INT_FIXED_ID = 'int_fixed_records.int_fixed_id';

    /**
     * the column name for the i01 field
     */
    public const COL_I01 = 'int_fixed_records.i01';

    /**
     * the column name for the i02 field
     */
    public const COL_I02 = 'int_fixed_records.i02';

    /**
     * the column name for the i03 field
     */
    public const COL_I03 = 'int_fixed_records.i03';

    /**
     * the column name for the i04 field
     */
    public const COL_I04 = 'int_fixed_records.i04';

    /**
     * the column name for the i05 field
     */
    public const COL_I05 = 'int_fixed_records.i05';

    /**
     * the column name for the i06 field
     */
    public const COL_I06 = 'int_fixed_records.i06';

    /**
     * the column name for the i07 field
     */
    public const COL_I07 = 'int_fixed_records.i07';

    /**
     * the column name for the i08 field
     */
    public const COL_I08 = 'int_fixed_records.i08';

    /**
     * the column name for the i09 field
     */
    public const COL_I09 = 'int_fixed_records.i09';

    /**
     * the column name for the i10 field
     */
    public const COL_I10 = 'int_fixed_records.i10';

    /**
     * the column name for the i11 field
     */
    public const COL_I11 = 'int_fixed_records.i11';

    /**
     * the column name for the i12 field
     */
    public const COL_I12 = 'int_fixed_records.i12';

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
        self::TYPE_PHPNAME       => ['IntFixedId', 'I01', 'I02', 'I03', 'I04', 'I05', 'I06', 'I07', 'I08', 'I09', 'I10', 'I11', 'I12', ],
        self::TYPE_CAMELNAME     => ['intFixedId', 'i01', 'i02', 'i03', 'i04', 'i05', 'i06', 'i07', 'i08', 'i09', 'i10', 'i11', 'i12', ],
        self::TYPE_COLNAME       => [IntFixedTableMap::COL_INT_FIXED_ID, IntFixedTableMap::COL_I01, IntFixedTableMap::COL_I02, IntFixedTableMap::COL_I03, IntFixedTableMap::COL_I04, IntFixedTableMap::COL_I05, IntFixedTableMap::COL_I06, IntFixedTableMap::COL_I07, IntFixedTableMap::COL_I08, IntFixedTableMap::COL_I09, IntFixedTableMap::COL_I10, IntFixedTableMap::COL_I11, IntFixedTableMap::COL_I12, ],
        self::TYPE_FIELDNAME     => ['int_fixed_id', 'i01', 'i02', 'i03', 'i04', 'i05', 'i06', 'i07', 'i08', 'i09', 'i10', 'i11', 'i12', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
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
        self::TYPE_PHPNAME       => ['IntFixedId' => 0, 'I01' => 1, 'I02' => 2, 'I03' => 3, 'I04' => 4, 'I05' => 5, 'I06' => 6, 'I07' => 7, 'I08' => 8, 'I09' => 9, 'I10' => 10, 'I11' => 11, 'I12' => 12, ],
        self::TYPE_CAMELNAME     => ['intFixedId' => 0, 'i01' => 1, 'i02' => 2, 'i03' => 3, 'i04' => 4, 'i05' => 5, 'i06' => 6, 'i07' => 7, 'i08' => 8, 'i09' => 9, 'i10' => 10, 'i11' => 11, 'i12' => 12, ],
        self::TYPE_COLNAME       => [IntFixedTableMap::COL_INT_FIXED_ID => 0, IntFixedTableMap::COL_I01 => 1, IntFixedTableMap::COL_I02 => 2, IntFixedTableMap::COL_I03 => 3, IntFixedTableMap::COL_I04 => 4, IntFixedTableMap::COL_I05 => 5, IntFixedTableMap::COL_I06 => 6, IntFixedTableMap::COL_I07 => 7, IntFixedTableMap::COL_I08 => 8, IntFixedTableMap::COL_I09 => 9, IntFixedTableMap::COL_I10 => 10, IntFixedTableMap::COL_I11 => 11, IntFixedTableMap::COL_I12 => 12, ],
        self::TYPE_FIELDNAME     => ['int_fixed_id' => 0, 'i01' => 1, 'i02' => 2, 'i03' => 3, 'i04' => 4, 'i05' => 5, 'i06' => 6, 'i07' => 7, 'i08' => 8, 'i09' => 9, 'i10' => 10, 'i11' => 11, 'i12' => 12, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'IntFixedId' => 'INT_FIXED_ID',
        'IntFixed.IntFixedId' => 'INT_FIXED_ID',
        'intFixedId' => 'INT_FIXED_ID',
        'intFixed.intFixedId' => 'INT_FIXED_ID',
        'IntFixedTableMap::COL_INT_FIXED_ID' => 'INT_FIXED_ID',
        'COL_INT_FIXED_ID' => 'INT_FIXED_ID',
        'int_fixed_id' => 'INT_FIXED_ID',
        'int_fixed_records.int_fixed_id' => 'INT_FIXED_ID',
        'I01' => 'I01',
        'IntFixed.I01' => 'I01',
        'i01' => 'I01',
        'intFixed.i01' => 'I01',
        'IntFixedTableMap::COL_I01' => 'I01',
        'COL_I01' => 'I01',
        'int_fixed_records.i01' => 'I01',
        'I02' => 'I02',
        'IntFixed.I02' => 'I02',
        'i02' => 'I02',
        'intFixed.i02' => 'I02',
        'IntFixedTableMap::COL_I02' => 'I02',
        'COL_I02' => 'I02',
        'int_fixed_records.i02' => 'I02',
        'I03' => 'I03',
        'IntFixed.I03' => 'I03',
        'i03' => 'I03',
        'intFixed.i03' => 'I03',
        'IntFixedTableMap::COL_I03' => 'I03',
        'COL_I03' => 'I03',
        'int_fixed_records.i03' => 'I03',
        'I04' => 'I04',
        'IntFixed.I04' => 'I04',
        'i04' => 'I04',
        'intFixed.i04' => 'I04',
        'IntFixedTableMap::COL_I04' => 'I04',
        'COL_I04' => 'I04',
        'int_fixed_records.i04' => 'I04',
        'I05' => 'I05',
        'IntFixed.I05' => 'I05',
        'i05' => 'I05',
        'intFixed.i05' => 'I05',
        'IntFixedTableMap::COL_I05' => 'I05',
        'COL_I05' => 'I05',
        'int_fixed_records.i05' => 'I05',
        'I06' => 'I06',
        'IntFixed.I06' => 'I06',
        'i06' => 'I06',
        'intFixed.i06' => 'I06',
        'IntFixedTableMap::COL_I06' => 'I06',
        'COL_I06' => 'I06',
        'int_fixed_records.i06' => 'I06',
        'I07' => 'I07',
        'IntFixed.I07' => 'I07',
        'i07' => 'I07',
        'intFixed.i07' => 'I07',
        'IntFixedTableMap::COL_I07' => 'I07',
        'COL_I07' => 'I07',
        'int_fixed_records.i07' => 'I07',
        'I08' => 'I08',
        'IntFixed.I08' => 'I08',
        'i08' => 'I08',
        'intFixed.i08' => 'I08',
        'IntFixedTableMap::COL_I08' => 'I08',
        'COL_I08' => 'I08',
        'int_fixed_records.i08' => 'I08',
        'I09' => 'I09',
        'IntFixed.I09' => 'I09',
        'i09' => 'I09',
        'intFixed.i09' => 'I09',
        'IntFixedTableMap::COL_I09' => 'I09',
        'COL_I09' => 'I09',
        'int_fixed_records.i09' => 'I09',
        'I10' => 'I10',
        'IntFixed.I10' => 'I10',
        'i10' => 'I10',
        'intFixed.i10' => 'I10',
        'IntFixedTableMap::COL_I10' => 'I10',
        'COL_I10' => 'I10',
        'int_fixed_records.i10' => 'I10',
        'I11' => 'I11',
        'IntFixed.I11' => 'I11',
        'i11' => 'I11',
        'intFixed.i11' => 'I11',
        'IntFixedTableMap::COL_I11' => 'I11',
        'COL_I11' => 'I11',
        'int_fixed_records.i11' => 'I11',
        'I12' => 'I12',
        'IntFixed.I12' => 'I12',
        'i12' => 'I12',
        'intFixed.i12' => 'I12',
        'IntFixedTableMap::COL_I12' => 'I12',
        'COL_I12' => 'I12',
        'int_fixed_records.i12' => 'I12',
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
        $this->setName('int_fixed_records');
        $this->setPhpName('IntFixed');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ThePHPBench\\Propel2\\IntFixed');
        $this->setPackage('ThePHPBench.Propel2');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('int_fixed_id', 'IntFixedId', 'INTEGER', true, null, null);
        $this->addColumn('i01', 'I01', 'INTEGER', true, null, null);
        $this->addColumn('i02', 'I02', 'INTEGER', true, null, null);
        $this->addColumn('i03', 'I03', 'INTEGER', true, null, null);
        $this->addColumn('i04', 'I04', 'INTEGER', true, null, null);
        $this->addColumn('i05', 'I05', 'INTEGER', true, null, null);
        $this->addColumn('i06', 'I06', 'INTEGER', true, null, null);
        $this->addColumn('i07', 'I07', 'INTEGER', true, null, null);
        $this->addColumn('i08', 'I08', 'INTEGER', true, null, null);
        $this->addColumn('i09', 'I09', 'INTEGER', true, null, null);
        $this->addColumn('i10', 'I10', 'INTEGER', true, null, null);
        $this->addColumn('i11', 'I11', 'INTEGER', true, null, null);
        $this->addColumn('i12', 'I12', 'INTEGER', true, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? IntFixedTableMap::CLASS_DEFAULT : IntFixedTableMap::OM_CLASS;
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
     * @return array (IntFixed object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = IntFixedTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = IntFixedTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + IntFixedTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = IntFixedTableMap::OM_CLASS;
            /** @var IntFixed $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            IntFixedTableMap::addInstanceToPool($obj, $key);
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
            $key = IntFixedTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = IntFixedTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var IntFixed $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                IntFixedTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(IntFixedTableMap::COL_INT_FIXED_ID);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I01);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I02);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I03);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I04);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I05);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I06);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I07);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I08);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I09);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I10);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I11);
            $criteria->addSelectColumn(IntFixedTableMap::COL_I12);
        } else {
            $criteria->addSelectColumn($alias . '.int_fixed_id');
            $criteria->addSelectColumn($alias . '.i01');
            $criteria->addSelectColumn($alias . '.i02');
            $criteria->addSelectColumn($alias . '.i03');
            $criteria->addSelectColumn($alias . '.i04');
            $criteria->addSelectColumn($alias . '.i05');
            $criteria->addSelectColumn($alias . '.i06');
            $criteria->addSelectColumn($alias . '.i07');
            $criteria->addSelectColumn($alias . '.i08');
            $criteria->addSelectColumn($alias . '.i09');
            $criteria->addSelectColumn($alias . '.i10');
            $criteria->addSelectColumn($alias . '.i11');
            $criteria->addSelectColumn($alias . '.i12');
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
            $criteria->removeSelectColumn(IntFixedTableMap::COL_INT_FIXED_ID);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I01);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I02);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I03);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I04);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I05);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I06);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I07);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I08);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I09);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I10);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I11);
            $criteria->removeSelectColumn(IntFixedTableMap::COL_I12);
        } else {
            $criteria->removeSelectColumn($alias . '.int_fixed_id');
            $criteria->removeSelectColumn($alias . '.i01');
            $criteria->removeSelectColumn($alias . '.i02');
            $criteria->removeSelectColumn($alias . '.i03');
            $criteria->removeSelectColumn($alias . '.i04');
            $criteria->removeSelectColumn($alias . '.i05');
            $criteria->removeSelectColumn($alias . '.i06');
            $criteria->removeSelectColumn($alias . '.i07');
            $criteria->removeSelectColumn($alias . '.i08');
            $criteria->removeSelectColumn($alias . '.i09');
            $criteria->removeSelectColumn($alias . '.i10');
            $criteria->removeSelectColumn($alias . '.i11');
            $criteria->removeSelectColumn($alias . '.i12');
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
        return Propel::getServiceContainer()->getDatabaseMap(IntFixedTableMap::DATABASE_NAME)->getTable(IntFixedTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a IntFixed or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or IntFixed object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ThePHPBench\Propel2\IntFixed) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(IntFixedTableMap::DATABASE_NAME);
            $criteria->add(IntFixedTableMap::COL_INT_FIXED_ID, (array) $values, Criteria::IN);
        }

        $query = IntFixedQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            IntFixedTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                IntFixedTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the int_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return IntFixedQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a IntFixed or Criteria object.
     *
     * @param mixed $criteria Criteria or IntFixed object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from IntFixed object
        }

        if ($criteria->containsKey(IntFixedTableMap::COL_INT_FIXED_ID) && $criteria->keyContainsValue(IntFixedTableMap::COL_INT_FIXED_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.IntFixedTableMap::COL_INT_FIXED_ID.')');
        }


        // Set the correct dbName
        $query = IntFixedQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
