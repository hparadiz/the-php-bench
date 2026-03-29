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
use ThePHPBench\Propel2\FloatFixed;
use ThePHPBench\Propel2\FloatFixedQuery;


/**
 * This class defines the structure of the 'float_fixed_records' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class FloatFixedTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'ThePHPBench.Propel2.Map.FloatFixedTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'float_fixed_records';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'FloatFixed';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\ThePHPBench\\Propel2\\FloatFixed';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'ThePHPBench.Propel2.FloatFixed';

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
     * the column name for the float_fixed_id field
     */
    public const COL_FLOAT_FIXED_ID = 'float_fixed_records.float_fixed_id';

    /**
     * the column name for the f01 field
     */
    public const COL_F01 = 'float_fixed_records.f01';

    /**
     * the column name for the f02 field
     */
    public const COL_F02 = 'float_fixed_records.f02';

    /**
     * the column name for the d01 field
     */
    public const COL_D01 = 'float_fixed_records.d01';

    /**
     * the column name for the d02 field
     */
    public const COL_D02 = 'float_fixed_records.d02';

    /**
     * the column name for the n01 field
     */
    public const COL_N01 = 'float_fixed_records.n01';

    /**
     * the column name for the n02 field
     */
    public const COL_N02 = 'float_fixed_records.n02';

    /**
     * the column name for the n03 field
     */
    public const COL_N03 = 'float_fixed_records.n03';

    /**
     * the column name for the n04 field
     */
    public const COL_N04 = 'float_fixed_records.n04';

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
        self::TYPE_PHPNAME       => ['FloatFixedId', 'F01', 'F02', 'D01', 'D02', 'N01', 'N02', 'N03', 'N04', ],
        self::TYPE_CAMELNAME     => ['floatFixedId', 'f01', 'f02', 'd01', 'd02', 'n01', 'n02', 'n03', 'n04', ],
        self::TYPE_COLNAME       => [FloatFixedTableMap::COL_FLOAT_FIXED_ID, FloatFixedTableMap::COL_F01, FloatFixedTableMap::COL_F02, FloatFixedTableMap::COL_D01, FloatFixedTableMap::COL_D02, FloatFixedTableMap::COL_N01, FloatFixedTableMap::COL_N02, FloatFixedTableMap::COL_N03, FloatFixedTableMap::COL_N04, ],
        self::TYPE_FIELDNAME     => ['float_fixed_id', 'f01', 'f02', 'd01', 'd02', 'n01', 'n02', 'n03', 'n04', ],
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
        self::TYPE_PHPNAME       => ['FloatFixedId' => 0, 'F01' => 1, 'F02' => 2, 'D01' => 3, 'D02' => 4, 'N01' => 5, 'N02' => 6, 'N03' => 7, 'N04' => 8, ],
        self::TYPE_CAMELNAME     => ['floatFixedId' => 0, 'f01' => 1, 'f02' => 2, 'd01' => 3, 'd02' => 4, 'n01' => 5, 'n02' => 6, 'n03' => 7, 'n04' => 8, ],
        self::TYPE_COLNAME       => [FloatFixedTableMap::COL_FLOAT_FIXED_ID => 0, FloatFixedTableMap::COL_F01 => 1, FloatFixedTableMap::COL_F02 => 2, FloatFixedTableMap::COL_D01 => 3, FloatFixedTableMap::COL_D02 => 4, FloatFixedTableMap::COL_N01 => 5, FloatFixedTableMap::COL_N02 => 6, FloatFixedTableMap::COL_N03 => 7, FloatFixedTableMap::COL_N04 => 8, ],
        self::TYPE_FIELDNAME     => ['float_fixed_id' => 0, 'f01' => 1, 'f02' => 2, 'd01' => 3, 'd02' => 4, 'n01' => 5, 'n02' => 6, 'n03' => 7, 'n04' => 8, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'FloatFixedId' => 'FLOAT_FIXED_ID',
        'FloatFixed.FloatFixedId' => 'FLOAT_FIXED_ID',
        'floatFixedId' => 'FLOAT_FIXED_ID',
        'floatFixed.floatFixedId' => 'FLOAT_FIXED_ID',
        'FloatFixedTableMap::COL_FLOAT_FIXED_ID' => 'FLOAT_FIXED_ID',
        'COL_FLOAT_FIXED_ID' => 'FLOAT_FIXED_ID',
        'float_fixed_id' => 'FLOAT_FIXED_ID',
        'float_fixed_records.float_fixed_id' => 'FLOAT_FIXED_ID',
        'F01' => 'F01',
        'FloatFixed.F01' => 'F01',
        'f01' => 'F01',
        'floatFixed.f01' => 'F01',
        'FloatFixedTableMap::COL_F01' => 'F01',
        'COL_F01' => 'F01',
        'float_fixed_records.f01' => 'F01',
        'F02' => 'F02',
        'FloatFixed.F02' => 'F02',
        'f02' => 'F02',
        'floatFixed.f02' => 'F02',
        'FloatFixedTableMap::COL_F02' => 'F02',
        'COL_F02' => 'F02',
        'float_fixed_records.f02' => 'F02',
        'D01' => 'D01',
        'FloatFixed.D01' => 'D01',
        'd01' => 'D01',
        'floatFixed.d01' => 'D01',
        'FloatFixedTableMap::COL_D01' => 'D01',
        'COL_D01' => 'D01',
        'float_fixed_records.d01' => 'D01',
        'D02' => 'D02',
        'FloatFixed.D02' => 'D02',
        'd02' => 'D02',
        'floatFixed.d02' => 'D02',
        'FloatFixedTableMap::COL_D02' => 'D02',
        'COL_D02' => 'D02',
        'float_fixed_records.d02' => 'D02',
        'N01' => 'N01',
        'FloatFixed.N01' => 'N01',
        'n01' => 'N01',
        'floatFixed.n01' => 'N01',
        'FloatFixedTableMap::COL_N01' => 'N01',
        'COL_N01' => 'N01',
        'float_fixed_records.n01' => 'N01',
        'N02' => 'N02',
        'FloatFixed.N02' => 'N02',
        'n02' => 'N02',
        'floatFixed.n02' => 'N02',
        'FloatFixedTableMap::COL_N02' => 'N02',
        'COL_N02' => 'N02',
        'float_fixed_records.n02' => 'N02',
        'N03' => 'N03',
        'FloatFixed.N03' => 'N03',
        'n03' => 'N03',
        'floatFixed.n03' => 'N03',
        'FloatFixedTableMap::COL_N03' => 'N03',
        'COL_N03' => 'N03',
        'float_fixed_records.n03' => 'N03',
        'N04' => 'N04',
        'FloatFixed.N04' => 'N04',
        'n04' => 'N04',
        'floatFixed.n04' => 'N04',
        'FloatFixedTableMap::COL_N04' => 'N04',
        'COL_N04' => 'N04',
        'float_fixed_records.n04' => 'N04',
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
        $this->setName('float_fixed_records');
        $this->setPhpName('FloatFixed');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ThePHPBench\\Propel2\\FloatFixed');
        $this->setPackage('ThePHPBench.Propel2');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('float_fixed_id', 'FloatFixedId', 'INTEGER', true, null, null);
        $this->addColumn('f01', 'F01', 'FLOAT', true, null, null);
        $this->addColumn('f02', 'F02', 'FLOAT', true, null, null);
        $this->addColumn('d01', 'D01', 'DOUBLE', true, null, null);
        $this->addColumn('d02', 'D02', 'DOUBLE', true, null, null);
        $this->addColumn('n01', 'N01', 'DECIMAL', true, 10, null);
        $this->addColumn('n02', 'N02', 'DECIMAL', true, 12, null);
        $this->addColumn('n03', 'N03', 'DECIMAL', true, 18, null);
        $this->addColumn('n04', 'N04', 'DECIMAL', true, 20, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? FloatFixedTableMap::CLASS_DEFAULT : FloatFixedTableMap::OM_CLASS;
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
     * @return array (FloatFixed object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = FloatFixedTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = FloatFixedTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + FloatFixedTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FloatFixedTableMap::OM_CLASS;
            /** @var FloatFixed $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            FloatFixedTableMap::addInstanceToPool($obj, $key);
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
            $key = FloatFixedTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = FloatFixedTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var FloatFixed $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FloatFixedTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(FloatFixedTableMap::COL_FLOAT_FIXED_ID);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_F01);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_F02);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_D01);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_D02);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_N01);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_N02);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_N03);
            $criteria->addSelectColumn(FloatFixedTableMap::COL_N04);
        } else {
            $criteria->addSelectColumn($alias . '.float_fixed_id');
            $criteria->addSelectColumn($alias . '.f01');
            $criteria->addSelectColumn($alias . '.f02');
            $criteria->addSelectColumn($alias . '.d01');
            $criteria->addSelectColumn($alias . '.d02');
            $criteria->addSelectColumn($alias . '.n01');
            $criteria->addSelectColumn($alias . '.n02');
            $criteria->addSelectColumn($alias . '.n03');
            $criteria->addSelectColumn($alias . '.n04');
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
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_FLOAT_FIXED_ID);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_F01);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_F02);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_D01);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_D02);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_N01);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_N02);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_N03);
            $criteria->removeSelectColumn(FloatFixedTableMap::COL_N04);
        } else {
            $criteria->removeSelectColumn($alias . '.float_fixed_id');
            $criteria->removeSelectColumn($alias . '.f01');
            $criteria->removeSelectColumn($alias . '.f02');
            $criteria->removeSelectColumn($alias . '.d01');
            $criteria->removeSelectColumn($alias . '.d02');
            $criteria->removeSelectColumn($alias . '.n01');
            $criteria->removeSelectColumn($alias . '.n02');
            $criteria->removeSelectColumn($alias . '.n03');
            $criteria->removeSelectColumn($alias . '.n04');
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
        return Propel::getServiceContainer()->getDatabaseMap(FloatFixedTableMap::DATABASE_NAME)->getTable(FloatFixedTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a FloatFixed or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or FloatFixed object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ThePHPBench\Propel2\FloatFixed) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FloatFixedTableMap::DATABASE_NAME);
            $criteria->add(FloatFixedTableMap::COL_FLOAT_FIXED_ID, (array) $values, Criteria::IN);
        }

        $query = FloatFixedQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            FloatFixedTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                FloatFixedTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the float_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return FloatFixedQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a FloatFixed or Criteria object.
     *
     * @param mixed $criteria Criteria or FloatFixed object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from FloatFixed object
        }

        if ($criteria->containsKey(FloatFixedTableMap::COL_FLOAT_FIXED_ID) && $criteria->keyContainsValue(FloatFixedTableMap::COL_FLOAT_FIXED_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FloatFixedTableMap::COL_FLOAT_FIXED_ID.')');
        }


        // Set the correct dbName
        $query = FloatFixedQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
