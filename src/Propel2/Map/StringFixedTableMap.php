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
use ThePHPBench\Propel2\StringFixed;
use ThePHPBench\Propel2\StringFixedQuery;


/**
 * This class defines the structure of the 'string_fixed_records' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class StringFixedTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'ThePHPBench.Propel2.Map.StringFixedTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'string_fixed_records';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'StringFixed';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\ThePHPBench\\Propel2\\StringFixed';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'ThePHPBench.Propel2.StringFixed';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the string_fixed_id field
     */
    public const COL_STRING_FIXED_ID = 'string_fixed_records.string_fixed_id';

    /**
     * the column name for the c08_01 field
     */
    public const COL_C08_01 = 'string_fixed_records.c08_01';

    /**
     * the column name for the c08_02 field
     */
    public const COL_C08_02 = 'string_fixed_records.c08_02';

    /**
     * the column name for the c16_01 field
     */
    public const COL_C16_01 = 'string_fixed_records.c16_01';

    /**
     * the column name for the c16_02 field
     */
    public const COL_C16_02 = 'string_fixed_records.c16_02';

    /**
     * the column name for the c32_01 field
     */
    public const COL_C32_01 = 'string_fixed_records.c32_01';

    /**
     * the column name for the c32_02 field
     */
    public const COL_C32_02 = 'string_fixed_records.c32_02';

    /**
     * the column name for the c64_01 field
     */
    public const COL_C64_01 = 'string_fixed_records.c64_01';

    /**
     * the column name for the c64_02 field
     */
    public const COL_C64_02 = 'string_fixed_records.c64_02';

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
        self::TYPE_PHPNAME       => ['StringFixedId', 'C0801', 'C0802', 'C1601', 'C1602', 'C3201', 'C3202', 'C6401', 'C6402', ],
        self::TYPE_CAMELNAME     => ['stringFixedId', 'c0801', 'c0802', 'c1601', 'c1602', 'c3201', 'c3202', 'c6401', 'c6402', ],
        self::TYPE_COLNAME       => [StringFixedTableMap::COL_STRING_FIXED_ID, StringFixedTableMap::COL_C08_01, StringFixedTableMap::COL_C08_02, StringFixedTableMap::COL_C16_01, StringFixedTableMap::COL_C16_02, StringFixedTableMap::COL_C32_01, StringFixedTableMap::COL_C32_02, StringFixedTableMap::COL_C64_01, StringFixedTableMap::COL_C64_02, ],
        self::TYPE_FIELDNAME     => ['string_fixed_id', 'c08_01', 'c08_02', 'c16_01', 'c16_02', 'c32_01', 'c32_02', 'c64_01', 'c64_02', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
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
        self::TYPE_PHPNAME       => ['StringFixedId' => 0, 'C0801' => 1, 'C0802' => 2, 'C1601' => 3, 'C1602' => 4, 'C3201' => 5, 'C3202' => 6, 'C6401' => 7, 'C6402' => 8, ],
        self::TYPE_CAMELNAME     => ['stringFixedId' => 0, 'c0801' => 1, 'c0802' => 2, 'c1601' => 3, 'c1602' => 4, 'c3201' => 5, 'c3202' => 6, 'c6401' => 7, 'c6402' => 8, ],
        self::TYPE_COLNAME       => [StringFixedTableMap::COL_STRING_FIXED_ID => 0, StringFixedTableMap::COL_C08_01 => 1, StringFixedTableMap::COL_C08_02 => 2, StringFixedTableMap::COL_C16_01 => 3, StringFixedTableMap::COL_C16_02 => 4, StringFixedTableMap::COL_C32_01 => 5, StringFixedTableMap::COL_C32_02 => 6, StringFixedTableMap::COL_C64_01 => 7, StringFixedTableMap::COL_C64_02 => 8, ],
        self::TYPE_FIELDNAME     => ['string_fixed_id' => 0, 'c08_01' => 1, 'c08_02' => 2, 'c16_01' => 3, 'c16_02' => 4, 'c32_01' => 5, 'c32_02' => 6, 'c64_01' => 7, 'c64_02' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'StringFixedId' => 'STRING_FIXED_ID',
        'StringFixed.StringFixedId' => 'STRING_FIXED_ID',
        'stringFixedId' => 'STRING_FIXED_ID',
        'stringFixed.stringFixedId' => 'STRING_FIXED_ID',
        'StringFixedTableMap::COL_STRING_FIXED_ID' => 'STRING_FIXED_ID',
        'COL_STRING_FIXED_ID' => 'STRING_FIXED_ID',
        'string_fixed_id' => 'STRING_FIXED_ID',
        'string_fixed_records.string_fixed_id' => 'STRING_FIXED_ID',
        'C0801' => 'C08_01',
        'StringFixed.C0801' => 'C08_01',
        'c0801' => 'C08_01',
        'stringFixed.c0801' => 'C08_01',
        'StringFixedTableMap::COL_C08_01' => 'C08_01',
        'COL_C08_01' => 'C08_01',
        'c08_01' => 'C08_01',
        'string_fixed_records.c08_01' => 'C08_01',
        'C0802' => 'C08_02',
        'StringFixed.C0802' => 'C08_02',
        'c0802' => 'C08_02',
        'stringFixed.c0802' => 'C08_02',
        'StringFixedTableMap::COL_C08_02' => 'C08_02',
        'COL_C08_02' => 'C08_02',
        'c08_02' => 'C08_02',
        'string_fixed_records.c08_02' => 'C08_02',
        'C1601' => 'C16_01',
        'StringFixed.C1601' => 'C16_01',
        'c1601' => 'C16_01',
        'stringFixed.c1601' => 'C16_01',
        'StringFixedTableMap::COL_C16_01' => 'C16_01',
        'COL_C16_01' => 'C16_01',
        'c16_01' => 'C16_01',
        'string_fixed_records.c16_01' => 'C16_01',
        'C1602' => 'C16_02',
        'StringFixed.C1602' => 'C16_02',
        'c1602' => 'C16_02',
        'stringFixed.c1602' => 'C16_02',
        'StringFixedTableMap::COL_C16_02' => 'C16_02',
        'COL_C16_02' => 'C16_02',
        'c16_02' => 'C16_02',
        'string_fixed_records.c16_02' => 'C16_02',
        'C3201' => 'C32_01',
        'StringFixed.C3201' => 'C32_01',
        'c3201' => 'C32_01',
        'stringFixed.c3201' => 'C32_01',
        'StringFixedTableMap::COL_C32_01' => 'C32_01',
        'COL_C32_01' => 'C32_01',
        'c32_01' => 'C32_01',
        'string_fixed_records.c32_01' => 'C32_01',
        'C3202' => 'C32_02',
        'StringFixed.C3202' => 'C32_02',
        'c3202' => 'C32_02',
        'stringFixed.c3202' => 'C32_02',
        'StringFixedTableMap::COL_C32_02' => 'C32_02',
        'COL_C32_02' => 'C32_02',
        'c32_02' => 'C32_02',
        'string_fixed_records.c32_02' => 'C32_02',
        'C6401' => 'C64_01',
        'StringFixed.C6401' => 'C64_01',
        'c6401' => 'C64_01',
        'stringFixed.c6401' => 'C64_01',
        'StringFixedTableMap::COL_C64_01' => 'C64_01',
        'COL_C64_01' => 'C64_01',
        'c64_01' => 'C64_01',
        'string_fixed_records.c64_01' => 'C64_01',
        'C6402' => 'C64_02',
        'StringFixed.C6402' => 'C64_02',
        'c6402' => 'C64_02',
        'stringFixed.c6402' => 'C64_02',
        'StringFixedTableMap::COL_C64_02' => 'C64_02',
        'COL_C64_02' => 'C64_02',
        'c64_02' => 'C64_02',
        'string_fixed_records.c64_02' => 'C64_02',
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
        $this->setName('string_fixed_records');
        $this->setPhpName('StringFixed');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ThePHPBench\\Propel2\\StringFixed');
        $this->setPackage('ThePHPBench.Propel2');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('string_fixed_id', 'StringFixedId', 'INTEGER', true, null, null);
        $this->addColumn('c08_01', 'C0801', 'CHAR', true, 8, null);
        $this->addColumn('c08_02', 'C0802', 'CHAR', true, 8, null);
        $this->addColumn('c16_01', 'C1601', 'CHAR', true, 16, null);
        $this->addColumn('c16_02', 'C1602', 'CHAR', true, 16, null);
        $this->addColumn('c32_01', 'C3201', 'CHAR', true, 32, null);
        $this->addColumn('c32_02', 'C3202', 'CHAR', true, 32, null);
        $this->addColumn('c64_01', 'C6401', 'CHAR', true, 64, null);
        $this->addColumn('c64_02', 'C6402', 'CHAR', true, 64, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('StringFixedId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? StringFixedTableMap::CLASS_DEFAULT : StringFixedTableMap::OM_CLASS;
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
     * @return array (StringFixed object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = StringFixedTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StringFixedTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StringFixedTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StringFixedTableMap::OM_CLASS;
            /** @var StringFixed $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StringFixedTableMap::addInstanceToPool($obj, $key);
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
            $key = StringFixedTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StringFixedTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var StringFixed $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StringFixedTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StringFixedTableMap::COL_STRING_FIXED_ID);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C08_01);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C08_02);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C16_01);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C16_02);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C32_01);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C32_02);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C64_01);
            $criteria->addSelectColumn(StringFixedTableMap::COL_C64_02);
        } else {
            $criteria->addSelectColumn($alias . '.string_fixed_id');
            $criteria->addSelectColumn($alias . '.c08_01');
            $criteria->addSelectColumn($alias . '.c08_02');
            $criteria->addSelectColumn($alias . '.c16_01');
            $criteria->addSelectColumn($alias . '.c16_02');
            $criteria->addSelectColumn($alias . '.c32_01');
            $criteria->addSelectColumn($alias . '.c32_02');
            $criteria->addSelectColumn($alias . '.c64_01');
            $criteria->addSelectColumn($alias . '.c64_02');
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
            $criteria->removeSelectColumn(StringFixedTableMap::COL_STRING_FIXED_ID);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C08_01);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C08_02);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C16_01);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C16_02);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C32_01);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C32_02);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C64_01);
            $criteria->removeSelectColumn(StringFixedTableMap::COL_C64_02);
        } else {
            $criteria->removeSelectColumn($alias . '.string_fixed_id');
            $criteria->removeSelectColumn($alias . '.c08_01');
            $criteria->removeSelectColumn($alias . '.c08_02');
            $criteria->removeSelectColumn($alias . '.c16_01');
            $criteria->removeSelectColumn($alias . '.c16_02');
            $criteria->removeSelectColumn($alias . '.c32_01');
            $criteria->removeSelectColumn($alias . '.c32_02');
            $criteria->removeSelectColumn($alias . '.c64_01');
            $criteria->removeSelectColumn($alias . '.c64_02');
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
        return Propel::getServiceContainer()->getDatabaseMap(StringFixedTableMap::DATABASE_NAME)->getTable(StringFixedTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a StringFixed or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or StringFixed object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StringFixedTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ThePHPBench\Propel2\StringFixed) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StringFixedTableMap::DATABASE_NAME);
            $criteria->add(StringFixedTableMap::COL_STRING_FIXED_ID, (array) $values, Criteria::IN);
        }

        $query = StringFixedQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StringFixedTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StringFixedTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the string_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return StringFixedQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a StringFixed or Criteria object.
     *
     * @param mixed $criteria Criteria or StringFixed object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StringFixedTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from StringFixed object
        }

        if ($criteria->containsKey(StringFixedTableMap::COL_STRING_FIXED_ID) && $criteria->keyContainsValue(StringFixedTableMap::COL_STRING_FIXED_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StringFixedTableMap::COL_STRING_FIXED_ID.')');
        }


        // Set the correct dbName
        $query = StringFixedQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
