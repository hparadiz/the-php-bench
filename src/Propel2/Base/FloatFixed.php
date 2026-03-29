<?php

namespace ThePHPBench\Propel2\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use ThePHPBench\Propel2\FloatFixedQuery as ChildFloatFixedQuery;
use ThePHPBench\Propel2\Map\FloatFixedTableMap;

/**
 * Base class that represents a row from the 'float_fixed_records' table.
 *
 *
 *
 * @package    propel.generator.ThePHPBench.Propel2.Base
 */
abstract class FloatFixed implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\ThePHPBench\\Propel2\\Map\\FloatFixedTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var bool
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var bool
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the float_fixed_id field.
     *
     * @var        int
     */
    protected $float_fixed_id;

    /**
     * The value for the f01 field.
     *
     * @var        double
     */
    protected $f01;

    /**
     * The value for the f02 field.
     *
     * @var        double
     */
    protected $f02;

    /**
     * The value for the d01 field.
     *
     * @var        double
     */
    protected $d01;

    /**
     * The value for the d02 field.
     *
     * @var        double
     */
    protected $d02;

    /**
     * The value for the n01 field.
     *
     * @var        string
     */
    protected $n01;

    /**
     * The value for the n02 field.
     *
     * @var        string
     */
    protected $n02;

    /**
     * The value for the n03 field.
     *
     * @var        string
     */
    protected $n03;

    /**
     * The value for the n04 field.
     *
     * @var        string
     */
    protected $n04;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of ThePHPBench\Propel2\Base\FloatFixed object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return bool True if the object has been modified.
     */
    public function isModified(): bool
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return bool True if $col has been modified.
     */
    public function isColumnModified(string $col): bool
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns(): array
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return bool True, if the object has never been persisted.
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param bool $b the state of the object.
     */
    public function setNew(bool $b): void
    {
        $this->new = $b;
    }

    /**
     * Whether this object has been deleted.
     * @return bool The deleted state of this object.
     */
    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param bool $b The deleted state of this object.
     * @return void
     */
    public function setDeleted(bool $b): void
    {
        $this->deleted = $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified(?string $col = null): void
    {
        if (null !== $col) {
            unset($this->modifiedColumns[$col]);
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>FloatFixed</code> instance.  If
     * <code>obj</code> is an instance of <code>FloatFixed</code>, delegates to
     * <code>equals(FloatFixed)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param mixed $obj The object to compare to.
     * @return bool Whether equal to the object specified.
     */
    public function equals($obj): bool
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns(): array
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return bool
     */
    public function hasVirtualColumn(string $name): bool
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @return mixed
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getVirtualColumn(string $name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of nonexistent virtual column `%s`.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name The virtual column name
     * @param mixed $value The value to give to the virtual column
     *
     * @return $this The current object, for fluid interface
     */
    public function setVirtualColumn(string $name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param string $msg
     * @param int $priority One of the Propel::LOG_* logging levels
     * @return void
     */
    protected function log(string $msg, int $priority = Propel::LOG_INFO): void
    {
        Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param \Propel\Runtime\Parser\AbstractParser|string $parser An AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME, TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM. Defaults to TableMap::TYPE_PHPNAME.
     * @return string The exported data
     */
    public function exportTo($parser, bool $includeLazyLoadColumns = true, string $keyType = TableMap::TYPE_PHPNAME): string
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray($keyType, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     *
     * @return array<string>
     */
    public function __sleep(): array
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [float_fixed_id] column value.
     *
     * @return int
     */
    public function getFloatFixedId()
    {
        return $this->float_fixed_id;
    }

    /**
     * Get the [f01] column value.
     *
     * @return double
     */
    public function getF01()
    {
        return $this->f01;
    }

    /**
     * Get the [f02] column value.
     *
     * @return double
     */
    public function getF02()
    {
        return $this->f02;
    }

    /**
     * Get the [d01] column value.
     *
     * @return double
     */
    public function getD01()
    {
        return $this->d01;
    }

    /**
     * Get the [d02] column value.
     *
     * @return double
     */
    public function getD02()
    {
        return $this->d02;
    }

    /**
     * Get the [n01] column value.
     *
     * @return string
     */
    public function getN01()
    {
        return $this->n01;
    }

    /**
     * Get the [n02] column value.
     *
     * @return string
     */
    public function getN02()
    {
        return $this->n02;
    }

    /**
     * Get the [n03] column value.
     *
     * @return string
     */
    public function getN03()
    {
        return $this->n03;
    }

    /**
     * Get the [n04] column value.
     *
     * @return string
     */
    public function getN04()
    {
        return $this->n04;
    }

    /**
     * Set the value of [float_fixed_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFloatFixedId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->float_fixed_id !== $v) {
            $this->float_fixed_id = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_FLOAT_FIXED_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [f01] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setF01($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->f01 !== $v) {
            $this->f01 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_F01] = true;
        }

        return $this;
    }

    /**
     * Set the value of [f02] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setF02($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->f02 !== $v) {
            $this->f02 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_F02] = true;
        }

        return $this;
    }

    /**
     * Set the value of [d01] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setD01($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->d01 !== $v) {
            $this->d01 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_D01] = true;
        }

        return $this;
    }

    /**
     * Set the value of [d02] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setD02($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->d02 !== $v) {
            $this->d02 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_D02] = true;
        }

        return $this;
    }

    /**
     * Set the value of [n01] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setN01($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->n01 !== $v) {
            $this->n01 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_N01] = true;
        }

        return $this;
    }

    /**
     * Set the value of [n02] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setN02($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->n02 !== $v) {
            $this->n02 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_N02] = true;
        }

        return $this;
    }

    /**
     * Set the value of [n03] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setN03($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->n03 !== $v) {
            $this->n03 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_N03] = true;
        }

        return $this;
    }

    /**
     * Set the value of [n04] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setN04($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->n04 !== $v) {
            $this->n04 = $v;
            $this->modifiedColumns[FloatFixedTableMap::COL_N04] = true;
        }

        return $this;
    }

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return bool Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues(): bool
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    }

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by DataFetcher->fetch().
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param bool $rehydrate Whether this object is being re-hydrated from the database.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int next starting column
     * @throws \Propel\Runtime\Exception\PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate(array $row, int $startcol = 0, bool $rehydrate = false, string $indexType = TableMap::TYPE_NUM): int
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : FloatFixedTableMap::translateFieldName('FloatFixedId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->float_fixed_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : FloatFixedTableMap::translateFieldName('F01', TableMap::TYPE_PHPNAME, $indexType)];
            $this->f01 = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : FloatFixedTableMap::translateFieldName('F02', TableMap::TYPE_PHPNAME, $indexType)];
            $this->f02 = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : FloatFixedTableMap::translateFieldName('D01', TableMap::TYPE_PHPNAME, $indexType)];
            $this->d01 = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : FloatFixedTableMap::translateFieldName('D02', TableMap::TYPE_PHPNAME, $indexType)];
            $this->d02 = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : FloatFixedTableMap::translateFieldName('N01', TableMap::TYPE_PHPNAME, $indexType)];
            $this->n01 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : FloatFixedTableMap::translateFieldName('N02', TableMap::TYPE_PHPNAME, $indexType)];
            $this->n02 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : FloatFixedTableMap::translateFieldName('N03', TableMap::TYPE_PHPNAME, $indexType)];
            $this->n03 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : FloatFixedTableMap::translateFieldName('N04', TableMap::TYPE_PHPNAME, $indexType)];
            $this->n04 = (null !== $col) ? (string) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 9; // 9 = FloatFixedTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ThePHPBench\\Propel2\\FloatFixed'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function ensureConsistency(): void
    {
    }

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param bool $deep (optional) Whether to also de-associated any related objects.
     * @param ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload(bool $deep = false, ?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildFloatFixedQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param ConnectionInterface $con
     * @return void
     * @throws \Propel\Runtime\Exception\PropelException
     * @see FloatFixed::setDeleted()
     * @see FloatFixed::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildFloatFixedQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    public function save(?ConnectionInterface $con = null): int
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($this->alreadyInSave) {
            return 0;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                FloatFixedTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param ConnectionInterface $con
     * @return int The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws \Propel\Runtime\Exception\PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con): int
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    }

    /**
     * Insert the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con): void
    {
        $modifiedColumns = [];
        $index = 0;

        if (null === $this->float_fixed_id) {
            throw new PropelException('Cannot insert FloatFixed without a primary key value.');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FloatFixedTableMap::COL_FLOAT_FIXED_ID)) {
            $modifiedColumns[':p' . $index++]  = 'float_fixed_id';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_F01)) {
            $modifiedColumns[':p' . $index++]  = 'f01';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_F02)) {
            $modifiedColumns[':p' . $index++]  = 'f02';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_D01)) {
            $modifiedColumns[':p' . $index++]  = 'd01';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_D02)) {
            $modifiedColumns[':p' . $index++]  = 'd02';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N01)) {
            $modifiedColumns[':p' . $index++]  = 'n01';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N02)) {
            $modifiedColumns[':p' . $index++]  = 'n02';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N03)) {
            $modifiedColumns[':p' . $index++]  = 'n03';
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N04)) {
            $modifiedColumns[':p' . $index++]  = 'n04';
        }

        $sql = sprintf(
            'INSERT INTO float_fixed_records (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'float_fixed_id':
                        $stmt->bindValue($identifier, $this->float_fixed_id, PDO::PARAM_INT);

                        break;
                    case 'f01':
                        $stmt->bindValue($identifier, $this->f01, PDO::PARAM_STR);

                        break;
                    case 'f02':
                        $stmt->bindValue($identifier, $this->f02, PDO::PARAM_STR);

                        break;
                    case 'd01':
                        $stmt->bindValue($identifier, $this->d01, PDO::PARAM_STR);

                        break;
                    case 'd02':
                        $stmt->bindValue($identifier, $this->d02, PDO::PARAM_STR);

                        break;
                    case 'n01':
                        $stmt->bindValue($identifier, $this->n01, PDO::PARAM_STR);

                        break;
                    case 'n02':
                        $stmt->bindValue($identifier, $this->n02, PDO::PARAM_STR);

                        break;
                    case 'n03':
                        $stmt->bindValue($identifier, $this->n03, PDO::PARAM_STR);

                        break;
                    case 'n04':
                        $stmt->bindValue($identifier, $this->n04, PDO::PARAM_STR);

                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param ConnectionInterface $con
     *
     * @return int Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con): int
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName(string $name, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FloatFixedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos Position in XML schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition(int $pos)
    {
        switch ($pos) {
            case 0:
                return $this->getFloatFixedId();

            case 1:
                return $this->getF01();

            case 2:
                return $this->getF02();

            case 3:
                return $this->getD01();

            case 4:
                return $this->getD02();

            case 5:
                return $this->getN01();

            case 6:
                return $this->getN02();

            case 7:
                return $this->getN03();

            case 8:
                return $this->getN04();

            default:
                return null;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param string $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param bool $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param array $alreadyDumpedObjects List of objects to skip to avoid recursion
     *
     * @return array An associative array containing the field names (as keys) and field values
     */
    public function toArray(string $keyType = TableMap::TYPE_PHPNAME, bool $includeLazyLoadColumns = true, array $alreadyDumpedObjects = []): array
    {
        if (isset($alreadyDumpedObjects['FloatFixed'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['FloatFixed'][$this->hashCode()] = true;
        $keys = FloatFixedTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getFloatFixedId(),
            $keys[1] => $this->getF01(),
            $keys[2] => $this->getF02(),
            $keys[3] => $this->getD01(),
            $keys[4] => $this->getD02(),
            $keys[5] => $this->getN01(),
            $keys[6] => $this->getN02(),
            $keys[7] => $this->getN03(),
            $keys[8] => $this->getN04(),
        ];
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }


        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this
     */
    public function setByName(string $name, $value, string $type = TableMap::TYPE_PHPNAME)
    {
        $pos = FloatFixedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        $this->setByPosition($pos, $value);

        return $this;
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return $this
     */
    public function setByPosition(int $pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setFloatFixedId($value);
                break;
            case 1:
                $this->setF01($value);
                break;
            case 2:
                $this->setF02($value);
                break;
            case 3:
                $this->setD01($value);
                break;
            case 4:
                $this->setD02($value);
                break;
            case 5:
                $this->setN01($value);
                break;
            case 6:
                $this->setN02($value);
                break;
            case 7:
                $this->setN03($value);
                break;
            case 8:
                $this->setN04($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param array $arr An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return $this
     */
    public function fromArray(array $arr, string $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = FloatFixedTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setFloatFixedId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setF01($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setF02($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setD01($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setD02($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setN01($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setN02($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setN03($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setN04($arr[$keys[8]]);
        }

        return $this;
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this The current object, for fluid interface
     */
    public function importFrom($parser, string $data, string $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria(): Criteria
    {
        $criteria = new Criteria(FloatFixedTableMap::DATABASE_NAME);

        if ($this->isColumnModified(FloatFixedTableMap::COL_FLOAT_FIXED_ID)) {
            $criteria->add(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $this->float_fixed_id);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_F01)) {
            $criteria->add(FloatFixedTableMap::COL_F01, $this->f01);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_F02)) {
            $criteria->add(FloatFixedTableMap::COL_F02, $this->f02);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_D01)) {
            $criteria->add(FloatFixedTableMap::COL_D01, $this->d01);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_D02)) {
            $criteria->add(FloatFixedTableMap::COL_D02, $this->d02);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N01)) {
            $criteria->add(FloatFixedTableMap::COL_N01, $this->n01);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N02)) {
            $criteria->add(FloatFixedTableMap::COL_N02, $this->n02);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N03)) {
            $criteria->add(FloatFixedTableMap::COL_N03, $this->n03);
        }
        if ($this->isColumnModified(FloatFixedTableMap::COL_N04)) {
            $criteria->add(FloatFixedTableMap::COL_N04, $this->n04);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria(): Criteria
    {
        $criteria = ChildFloatFixedQuery::create();
        $criteria->add(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $this->float_fixed_id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int|string Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getFloatFixedId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getFloatFixedId();
    }

    /**
     * Generic method to set the primary key (float_fixed_id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setFloatFixedId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getFloatFixedId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \ThePHPBench\Propel2\FloatFixed (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setF01($this->getF01());
        $copyObj->setF02($this->getF02());
        $copyObj->setD01($this->getD01());
        $copyObj->setD02($this->getD02());
        $copyObj->setN01($this->getN01());
        $copyObj->setN02($this->getN02());
        $copyObj->setN03($this->getN03());
        $copyObj->setN04($this->getN04());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setFloatFixedId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \ThePHPBench\Propel2\FloatFixed Clone of current object.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function copy(bool $deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     *
     * @return $this
     */
    public function clear()
    {
        $this->float_fixed_id = null;
        $this->f01 = null;
        $this->f02 = null;
        $this->d01 = null;
        $this->d02 = null;
        $this->n01 = null;
        $this->n02 = null;
        $this->n03 = null;
        $this->n04 = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);

        return $this;
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param bool $deep Whether to also clear the references on all referrer objects.
     * @return $this
     */
    public function clearAllReferences(bool $deep = false)
    {
        if ($deep) {
        } // if ($deep)

        return $this;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FloatFixedTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preSave(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postSave(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before inserting to database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preInsert(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postInsert(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before updating the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preUpdate(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postUpdate(?ConnectionInterface $con = null): void
    {
            }

    /**
     * Code to be run before deleting the object in database
     * @param ConnectionInterface|null $con
     * @return bool
     */
    public function preDelete(?ConnectionInterface $con = null): bool
    {
                return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface|null $con
     * @return void
     */
    public function postDelete(?ConnectionInterface $con = null): void
    {
            }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);
            $inputData = $params[0];
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->importFrom($format, $inputData, $keyType);
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = $params[0] ?? true;
            $keyType = $params[1] ?? TableMap::TYPE_PHPNAME;

            return $this->exportTo($format, $includeLazyLoadColumns, $keyType);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
