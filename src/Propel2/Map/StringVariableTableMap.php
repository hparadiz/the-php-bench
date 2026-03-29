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
use ThePHPBench\Propel2\StringVariable;
use ThePHPBench\Propel2\StringVariableQuery;


/**
 * This class defines the structure of the 'string_variable_records' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 */
class StringVariableTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    public const CLASS_NAME = 'ThePHPBench.Propel2.Map.StringVariableTableMap';

    /**
     * The default database name for this class
     */
    public const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    public const TABLE_NAME = 'string_variable_records';

    /**
     * The PHP name of this class (PascalCase)
     */
    public const TABLE_PHP_NAME = 'StringVariable';

    /**
     * The related Propel class for this table
     */
    public const OM_CLASS = '\\ThePHPBench\\Propel2\\StringVariable';

    /**
     * A class that can be returned by this tableMap
     */
    public const CLASS_DEFAULT = 'ThePHPBench.Propel2.StringVariable';

    /**
     * The total number of columns
     */
    public const NUM_COLUMNS = 11;

    /**
     * The number of lazy-loaded columns
     */
    public const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    public const NUM_HYDRATE_COLUMNS = 11;

    /**
     * the column name for the string_variable_id field
     */
    public const COL_STRING_VARIABLE_ID = 'string_variable_records.string_variable_id';

    /**
     * the column name for the name field
     */
    public const COL_NAME = 'string_variable_records.name';

    /**
     * the column name for the title field
     */
    public const COL_TITLE = 'string_variable_records.title';

    /**
     * the column name for the email field
     */
    public const COL_EMAIL = 'string_variable_records.email';

    /**
     * the column name for the company field
     */
    public const COL_COMPANY = 'string_variable_records.company';

    /**
     * the column name for the city field
     */
    public const COL_CITY = 'string_variable_records.city';

    /**
     * the column name for the region field
     */
    public const COL_REGION = 'string_variable_records.region';

    /**
     * the column name for the postal_code field
     */
    public const COL_POSTAL_CODE = 'string_variable_records.postal_code';

    /**
     * the column name for the country field
     */
    public const COL_COUNTRY = 'string_variable_records.country';

    /**
     * the column name for the phone field
     */
    public const COL_PHONE = 'string_variable_records.phone';

    /**
     * the column name for the notes field
     */
    public const COL_NOTES = 'string_variable_records.notes';

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
        self::TYPE_PHPNAME       => ['StringVariableId', 'Name', 'Title', 'Email', 'Company', 'City', 'Region', 'PostalCode', 'Country', 'Phone', 'Notes', ],
        self::TYPE_CAMELNAME     => ['stringVariableId', 'name', 'title', 'email', 'company', 'city', 'region', 'postalCode', 'country', 'phone', 'notes', ],
        self::TYPE_COLNAME       => [StringVariableTableMap::COL_STRING_VARIABLE_ID, StringVariableTableMap::COL_NAME, StringVariableTableMap::COL_TITLE, StringVariableTableMap::COL_EMAIL, StringVariableTableMap::COL_COMPANY, StringVariableTableMap::COL_CITY, StringVariableTableMap::COL_REGION, StringVariableTableMap::COL_POSTAL_CODE, StringVariableTableMap::COL_COUNTRY, StringVariableTableMap::COL_PHONE, StringVariableTableMap::COL_NOTES, ],
        self::TYPE_FIELDNAME     => ['string_variable_id', 'name', 'title', 'email', 'company', 'city', 'region', 'postal_code', 'country', 'phone', 'notes', ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
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
        self::TYPE_PHPNAME       => ['StringVariableId' => 0, 'Name' => 1, 'Title' => 2, 'Email' => 3, 'Company' => 4, 'City' => 5, 'Region' => 6, 'PostalCode' => 7, 'Country' => 8, 'Phone' => 9, 'Notes' => 10, ],
        self::TYPE_CAMELNAME     => ['stringVariableId' => 0, 'name' => 1, 'title' => 2, 'email' => 3, 'company' => 4, 'city' => 5, 'region' => 6, 'postalCode' => 7, 'country' => 8, 'phone' => 9, 'notes' => 10, ],
        self::TYPE_COLNAME       => [StringVariableTableMap::COL_STRING_VARIABLE_ID => 0, StringVariableTableMap::COL_NAME => 1, StringVariableTableMap::COL_TITLE => 2, StringVariableTableMap::COL_EMAIL => 3, StringVariableTableMap::COL_COMPANY => 4, StringVariableTableMap::COL_CITY => 5, StringVariableTableMap::COL_REGION => 6, StringVariableTableMap::COL_POSTAL_CODE => 7, StringVariableTableMap::COL_COUNTRY => 8, StringVariableTableMap::COL_PHONE => 9, StringVariableTableMap::COL_NOTES => 10, ],
        self::TYPE_FIELDNAME     => ['string_variable_id' => 0, 'name' => 1, 'title' => 2, 'email' => 3, 'company' => 4, 'city' => 5, 'region' => 6, 'postal_code' => 7, 'country' => 8, 'phone' => 9, 'notes' => 10, ],
        self::TYPE_NUM           => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, ]
    ];

    /**
     * Holds a list of column names and their normalized version.
     *
     * @var array<string>
     */
    protected $normalizedColumnNameMap = [
        'StringVariableId' => 'STRING_VARIABLE_ID',
        'StringVariable.StringVariableId' => 'STRING_VARIABLE_ID',
        'stringVariableId' => 'STRING_VARIABLE_ID',
        'stringVariable.stringVariableId' => 'STRING_VARIABLE_ID',
        'StringVariableTableMap::COL_STRING_VARIABLE_ID' => 'STRING_VARIABLE_ID',
        'COL_STRING_VARIABLE_ID' => 'STRING_VARIABLE_ID',
        'string_variable_id' => 'STRING_VARIABLE_ID',
        'string_variable_records.string_variable_id' => 'STRING_VARIABLE_ID',
        'Name' => 'NAME',
        'StringVariable.Name' => 'NAME',
        'name' => 'NAME',
        'stringVariable.name' => 'NAME',
        'StringVariableTableMap::COL_NAME' => 'NAME',
        'COL_NAME' => 'NAME',
        'string_variable_records.name' => 'NAME',
        'Title' => 'TITLE',
        'StringVariable.Title' => 'TITLE',
        'title' => 'TITLE',
        'stringVariable.title' => 'TITLE',
        'StringVariableTableMap::COL_TITLE' => 'TITLE',
        'COL_TITLE' => 'TITLE',
        'string_variable_records.title' => 'TITLE',
        'Email' => 'EMAIL',
        'StringVariable.Email' => 'EMAIL',
        'email' => 'EMAIL',
        'stringVariable.email' => 'EMAIL',
        'StringVariableTableMap::COL_EMAIL' => 'EMAIL',
        'COL_EMAIL' => 'EMAIL',
        'string_variable_records.email' => 'EMAIL',
        'Company' => 'COMPANY',
        'StringVariable.Company' => 'COMPANY',
        'company' => 'COMPANY',
        'stringVariable.company' => 'COMPANY',
        'StringVariableTableMap::COL_COMPANY' => 'COMPANY',
        'COL_COMPANY' => 'COMPANY',
        'string_variable_records.company' => 'COMPANY',
        'City' => 'CITY',
        'StringVariable.City' => 'CITY',
        'city' => 'CITY',
        'stringVariable.city' => 'CITY',
        'StringVariableTableMap::COL_CITY' => 'CITY',
        'COL_CITY' => 'CITY',
        'string_variable_records.city' => 'CITY',
        'Region' => 'REGION',
        'StringVariable.Region' => 'REGION',
        'region' => 'REGION',
        'stringVariable.region' => 'REGION',
        'StringVariableTableMap::COL_REGION' => 'REGION',
        'COL_REGION' => 'REGION',
        'string_variable_records.region' => 'REGION',
        'PostalCode' => 'POSTAL_CODE',
        'StringVariable.PostalCode' => 'POSTAL_CODE',
        'postalCode' => 'POSTAL_CODE',
        'stringVariable.postalCode' => 'POSTAL_CODE',
        'StringVariableTableMap::COL_POSTAL_CODE' => 'POSTAL_CODE',
        'COL_POSTAL_CODE' => 'POSTAL_CODE',
        'postal_code' => 'POSTAL_CODE',
        'string_variable_records.postal_code' => 'POSTAL_CODE',
        'Country' => 'COUNTRY',
        'StringVariable.Country' => 'COUNTRY',
        'country' => 'COUNTRY',
        'stringVariable.country' => 'COUNTRY',
        'StringVariableTableMap::COL_COUNTRY' => 'COUNTRY',
        'COL_COUNTRY' => 'COUNTRY',
        'string_variable_records.country' => 'COUNTRY',
        'Phone' => 'PHONE',
        'StringVariable.Phone' => 'PHONE',
        'phone' => 'PHONE',
        'stringVariable.phone' => 'PHONE',
        'StringVariableTableMap::COL_PHONE' => 'PHONE',
        'COL_PHONE' => 'PHONE',
        'string_variable_records.phone' => 'PHONE',
        'Notes' => 'NOTES',
        'StringVariable.Notes' => 'NOTES',
        'notes' => 'NOTES',
        'stringVariable.notes' => 'NOTES',
        'StringVariableTableMap::COL_NOTES' => 'NOTES',
        'COL_NOTES' => 'NOTES',
        'string_variable_records.notes' => 'NOTES',
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
        $this->setName('string_variable_records');
        $this->setPhpName('StringVariable');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\ThePHPBench\\Propel2\\StringVariable');
        $this->setPackage('ThePHPBench.Propel2');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('string_variable_id', 'StringVariableId', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 64, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 128, null);
        $this->addColumn('email', 'Email', 'VARCHAR', true, 160, null);
        $this->addColumn('company', 'Company', 'VARCHAR', true, 160, null);
        $this->addColumn('city', 'City', 'VARCHAR', true, 64, null);
        $this->addColumn('region', 'Region', 'VARCHAR', true, 64, null);
        $this->addColumn('postal_code', 'PostalCode', 'VARCHAR', true, 32, null);
        $this->addColumn('country', 'Country', 'VARCHAR', true, 64, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', true, 32, null);
        $this->addColumn('notes', 'Notes', 'LONGVARCHAR', true, null, null);
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)];
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
                : self::translateFieldName('StringVariableId', TableMap::TYPE_PHPNAME, $indexType)
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
        return $withPrefix ? StringVariableTableMap::CLASS_DEFAULT : StringVariableTableMap::OM_CLASS;
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
     * @return array (StringVariable object, last column rank)
     */
    public static function populateObject(array $row, int $offset = 0, string $indexType = TableMap::TYPE_NUM): array
    {
        $key = StringVariableTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StringVariableTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StringVariableTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StringVariableTableMap::OM_CLASS;
            /** @var StringVariable $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StringVariableTableMap::addInstanceToPool($obj, $key);
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
            $key = StringVariableTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StringVariableTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var StringVariable $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StringVariableTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(StringVariableTableMap::COL_STRING_VARIABLE_ID);
            $criteria->addSelectColumn(StringVariableTableMap::COL_NAME);
            $criteria->addSelectColumn(StringVariableTableMap::COL_TITLE);
            $criteria->addSelectColumn(StringVariableTableMap::COL_EMAIL);
            $criteria->addSelectColumn(StringVariableTableMap::COL_COMPANY);
            $criteria->addSelectColumn(StringVariableTableMap::COL_CITY);
            $criteria->addSelectColumn(StringVariableTableMap::COL_REGION);
            $criteria->addSelectColumn(StringVariableTableMap::COL_POSTAL_CODE);
            $criteria->addSelectColumn(StringVariableTableMap::COL_COUNTRY);
            $criteria->addSelectColumn(StringVariableTableMap::COL_PHONE);
            $criteria->addSelectColumn(StringVariableTableMap::COL_NOTES);
        } else {
            $criteria->addSelectColumn($alias . '.string_variable_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.company');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.region');
            $criteria->addSelectColumn($alias . '.postal_code');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.notes');
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
            $criteria->removeSelectColumn(StringVariableTableMap::COL_STRING_VARIABLE_ID);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_NAME);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_TITLE);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_EMAIL);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_COMPANY);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_CITY);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_REGION);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_POSTAL_CODE);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_COUNTRY);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_PHONE);
            $criteria->removeSelectColumn(StringVariableTableMap::COL_NOTES);
        } else {
            $criteria->removeSelectColumn($alias . '.string_variable_id');
            $criteria->removeSelectColumn($alias . '.name');
            $criteria->removeSelectColumn($alias . '.title');
            $criteria->removeSelectColumn($alias . '.email');
            $criteria->removeSelectColumn($alias . '.company');
            $criteria->removeSelectColumn($alias . '.city');
            $criteria->removeSelectColumn($alias . '.region');
            $criteria->removeSelectColumn($alias . '.postal_code');
            $criteria->removeSelectColumn($alias . '.country');
            $criteria->removeSelectColumn($alias . '.phone');
            $criteria->removeSelectColumn($alias . '.notes');
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
        return Propel::getServiceContainer()->getDatabaseMap(StringVariableTableMap::DATABASE_NAME)->getTable(StringVariableTableMap::TABLE_NAME);
    }

    /**
     * Performs a DELETE on the database, given a StringVariable or Criteria object OR a primary key value.
     *
     * @param mixed $values Criteria or StringVariable object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(StringVariableTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \ThePHPBench\Propel2\StringVariable) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StringVariableTableMap::DATABASE_NAME);
            $criteria->add(StringVariableTableMap::COL_STRING_VARIABLE_ID, (array) $values, Criteria::IN);
        }

        $query = StringVariableQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StringVariableTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                StringVariableTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the string_variable_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(?ConnectionInterface $con = null): int
    {
        return StringVariableQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a StringVariable or Criteria object.
     *
     * @param mixed $criteria Criteria or StringVariable object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed The new primary key.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StringVariableTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from StringVariable object
        }

        if ($criteria->containsKey(StringVariableTableMap::COL_STRING_VARIABLE_ID) && $criteria->keyContainsValue(StringVariableTableMap::COL_STRING_VARIABLE_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.StringVariableTableMap::COL_STRING_VARIABLE_ID.')');
        }


        // Set the correct dbName
        $query = StringVariableQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

}
