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
use ThePHPBench\Propel2\IntFixedQuery as ChildIntFixedQuery;
use ThePHPBench\Propel2\Map\IntFixedTableMap;

/**
 * Base class that represents a row from the 'int_fixed_records' table.
 *
 *
 *
 * @package    propel.generator.ThePHPBench.Propel2.Base
 */
abstract class IntFixed implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\ThePHPBench\\Propel2\\Map\\IntFixedTableMap';


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
     * The value for the int_fixed_id field.
     *
     * @var        int
     */
    protected $int_fixed_id;

    /**
     * The value for the i01 field.
     *
     * @var        int
     */
    protected $i01;

    /**
     * The value for the i02 field.
     *
     * @var        int
     */
    protected $i02;

    /**
     * The value for the i03 field.
     *
     * @var        int
     */
    protected $i03;

    /**
     * The value for the i04 field.
     *
     * @var        int
     */
    protected $i04;

    /**
     * The value for the i05 field.
     *
     * @var        int
     */
    protected $i05;

    /**
     * The value for the i06 field.
     *
     * @var        int
     */
    protected $i06;

    /**
     * The value for the i07 field.
     *
     * @var        int
     */
    protected $i07;

    /**
     * The value for the i08 field.
     *
     * @var        int
     */
    protected $i08;

    /**
     * The value for the i09 field.
     *
     * @var        int
     */
    protected $i09;

    /**
     * The value for the i10 field.
     *
     * @var        int
     */
    protected $i10;

    /**
     * The value for the i11 field.
     *
     * @var        int
     */
    protected $i11;

    /**
     * The value for the i12 field.
     *
     * @var        int
     */
    protected $i12;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of ThePHPBench\Propel2\Base\IntFixed object.
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
     * Compares this with another <code>IntFixed</code> instance.  If
     * <code>obj</code> is an instance of <code>IntFixed</code>, delegates to
     * <code>equals(IntFixed)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [int_fixed_id] column value.
     *
     * @return int
     */
    public function getIntFixedId()
    {
        return $this->int_fixed_id;
    }

    /**
     * Get the [i01] column value.
     *
     * @return int
     */
    public function getI01()
    {
        return $this->i01;
    }

    /**
     * Get the [i02] column value.
     *
     * @return int
     */
    public function getI02()
    {
        return $this->i02;
    }

    /**
     * Get the [i03] column value.
     *
     * @return int
     */
    public function getI03()
    {
        return $this->i03;
    }

    /**
     * Get the [i04] column value.
     *
     * @return int
     */
    public function getI04()
    {
        return $this->i04;
    }

    /**
     * Get the [i05] column value.
     *
     * @return int
     */
    public function getI05()
    {
        return $this->i05;
    }

    /**
     * Get the [i06] column value.
     *
     * @return int
     */
    public function getI06()
    {
        return $this->i06;
    }

    /**
     * Get the [i07] column value.
     *
     * @return int
     */
    public function getI07()
    {
        return $this->i07;
    }

    /**
     * Get the [i08] column value.
     *
     * @return int
     */
    public function getI08()
    {
        return $this->i08;
    }

    /**
     * Get the [i09] column value.
     *
     * @return int
     */
    public function getI09()
    {
        return $this->i09;
    }

    /**
     * Get the [i10] column value.
     *
     * @return int
     */
    public function getI10()
    {
        return $this->i10;
    }

    /**
     * Get the [i11] column value.
     *
     * @return int
     */
    public function getI11()
    {
        return $this->i11;
    }

    /**
     * Get the [i12] column value.
     *
     * @return int
     */
    public function getI12()
    {
        return $this->i12;
    }

    /**
     * Set the value of [int_fixed_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIntFixedId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->int_fixed_id !== $v) {
            $this->int_fixed_id = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_INT_FIXED_ID] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i01] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI01($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i01 !== $v) {
            $this->i01 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I01] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i02] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI02($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i02 !== $v) {
            $this->i02 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I02] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i03] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI03($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i03 !== $v) {
            $this->i03 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I03] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i04] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI04($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i04 !== $v) {
            $this->i04 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I04] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i05] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI05($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i05 !== $v) {
            $this->i05 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I05] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i06] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI06($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i06 !== $v) {
            $this->i06 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I06] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i07] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI07($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i07 !== $v) {
            $this->i07 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I07] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i08] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI08($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i08 !== $v) {
            $this->i08 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I08] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i09] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI09($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i09 !== $v) {
            $this->i09 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I09] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i10] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI10($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i10 !== $v) {
            $this->i10 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I10] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i11] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI11($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i11 !== $v) {
            $this->i11 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I11] = true;
        }

        return $this;
    }

    /**
     * Set the value of [i12] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setI12($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->i12 !== $v) {
            $this->i12 = $v;
            $this->modifiedColumns[IntFixedTableMap::COL_I12] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : IntFixedTableMap::translateFieldName('IntFixedId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->int_fixed_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : IntFixedTableMap::translateFieldName('I01', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i01 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : IntFixedTableMap::translateFieldName('I02', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i02 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : IntFixedTableMap::translateFieldName('I03', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i03 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : IntFixedTableMap::translateFieldName('I04', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i04 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : IntFixedTableMap::translateFieldName('I05', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i05 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : IntFixedTableMap::translateFieldName('I06', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i06 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : IntFixedTableMap::translateFieldName('I07', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i07 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : IntFixedTableMap::translateFieldName('I08', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i08 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : IntFixedTableMap::translateFieldName('I09', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i09 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : IntFixedTableMap::translateFieldName('I10', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i10 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : IntFixedTableMap::translateFieldName('I11', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i11 = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : IntFixedTableMap::translateFieldName('I12', TableMap::TYPE_PHPNAME, $indexType)];
            $this->i12 = (null !== $col) ? (int) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 13; // 13 = IntFixedTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ThePHPBench\\Propel2\\IntFixed'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(IntFixedTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildIntFixedQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see IntFixed::setDeleted()
     * @see IntFixed::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildIntFixedQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
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
                IntFixedTableMap::addInstanceToPool($this);
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

        if (null === $this->int_fixed_id) {
            throw new PropelException('Cannot insert IntFixed without a primary key value.');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(IntFixedTableMap::COL_INT_FIXED_ID)) {
            $modifiedColumns[':p' . $index++]  = 'int_fixed_id';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I01)) {
            $modifiedColumns[':p' . $index++]  = 'i01';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I02)) {
            $modifiedColumns[':p' . $index++]  = 'i02';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I03)) {
            $modifiedColumns[':p' . $index++]  = 'i03';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I04)) {
            $modifiedColumns[':p' . $index++]  = 'i04';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I05)) {
            $modifiedColumns[':p' . $index++]  = 'i05';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I06)) {
            $modifiedColumns[':p' . $index++]  = 'i06';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I07)) {
            $modifiedColumns[':p' . $index++]  = 'i07';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I08)) {
            $modifiedColumns[':p' . $index++]  = 'i08';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I09)) {
            $modifiedColumns[':p' . $index++]  = 'i09';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I10)) {
            $modifiedColumns[':p' . $index++]  = 'i10';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I11)) {
            $modifiedColumns[':p' . $index++]  = 'i11';
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I12)) {
            $modifiedColumns[':p' . $index++]  = 'i12';
        }

        $sql = sprintf(
            'INSERT INTO int_fixed_records (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'int_fixed_id':
                        $stmt->bindValue($identifier, $this->int_fixed_id, PDO::PARAM_INT);

                        break;
                    case 'i01':
                        $stmt->bindValue($identifier, $this->i01, PDO::PARAM_INT);

                        break;
                    case 'i02':
                        $stmt->bindValue($identifier, $this->i02, PDO::PARAM_INT);

                        break;
                    case 'i03':
                        $stmt->bindValue($identifier, $this->i03, PDO::PARAM_INT);

                        break;
                    case 'i04':
                        $stmt->bindValue($identifier, $this->i04, PDO::PARAM_INT);

                        break;
                    case 'i05':
                        $stmt->bindValue($identifier, $this->i05, PDO::PARAM_INT);

                        break;
                    case 'i06':
                        $stmt->bindValue($identifier, $this->i06, PDO::PARAM_INT);

                        break;
                    case 'i07':
                        $stmt->bindValue($identifier, $this->i07, PDO::PARAM_INT);

                        break;
                    case 'i08':
                        $stmt->bindValue($identifier, $this->i08, PDO::PARAM_INT);

                        break;
                    case 'i09':
                        $stmt->bindValue($identifier, $this->i09, PDO::PARAM_INT);

                        break;
                    case 'i10':
                        $stmt->bindValue($identifier, $this->i10, PDO::PARAM_INT);

                        break;
                    case 'i11':
                        $stmt->bindValue($identifier, $this->i11, PDO::PARAM_INT);

                        break;
                    case 'i12':
                        $stmt->bindValue($identifier, $this->i12, PDO::PARAM_INT);

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
        $pos = IntFixedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getIntFixedId();

            case 1:
                return $this->getI01();

            case 2:
                return $this->getI02();

            case 3:
                return $this->getI03();

            case 4:
                return $this->getI04();

            case 5:
                return $this->getI05();

            case 6:
                return $this->getI06();

            case 7:
                return $this->getI07();

            case 8:
                return $this->getI08();

            case 9:
                return $this->getI09();

            case 10:
                return $this->getI10();

            case 11:
                return $this->getI11();

            case 12:
                return $this->getI12();

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
        if (isset($alreadyDumpedObjects['IntFixed'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['IntFixed'][$this->hashCode()] = true;
        $keys = IntFixedTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getIntFixedId(),
            $keys[1] => $this->getI01(),
            $keys[2] => $this->getI02(),
            $keys[3] => $this->getI03(),
            $keys[4] => $this->getI04(),
            $keys[5] => $this->getI05(),
            $keys[6] => $this->getI06(),
            $keys[7] => $this->getI07(),
            $keys[8] => $this->getI08(),
            $keys[9] => $this->getI09(),
            $keys[10] => $this->getI10(),
            $keys[11] => $this->getI11(),
            $keys[12] => $this->getI12(),
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
        $pos = IntFixedTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setIntFixedId($value);
                break;
            case 1:
                $this->setI01($value);
                break;
            case 2:
                $this->setI02($value);
                break;
            case 3:
                $this->setI03($value);
                break;
            case 4:
                $this->setI04($value);
                break;
            case 5:
                $this->setI05($value);
                break;
            case 6:
                $this->setI06($value);
                break;
            case 7:
                $this->setI07($value);
                break;
            case 8:
                $this->setI08($value);
                break;
            case 9:
                $this->setI09($value);
                break;
            case 10:
                $this->setI10($value);
                break;
            case 11:
                $this->setI11($value);
                break;
            case 12:
                $this->setI12($value);
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
        $keys = IntFixedTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setIntFixedId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setI01($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setI02($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setI03($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setI04($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setI05($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setI06($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setI07($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setI08($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setI09($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setI10($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setI11($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setI12($arr[$keys[12]]);
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
        $criteria = new Criteria(IntFixedTableMap::DATABASE_NAME);

        if ($this->isColumnModified(IntFixedTableMap::COL_INT_FIXED_ID)) {
            $criteria->add(IntFixedTableMap::COL_INT_FIXED_ID, $this->int_fixed_id);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I01)) {
            $criteria->add(IntFixedTableMap::COL_I01, $this->i01);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I02)) {
            $criteria->add(IntFixedTableMap::COL_I02, $this->i02);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I03)) {
            $criteria->add(IntFixedTableMap::COL_I03, $this->i03);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I04)) {
            $criteria->add(IntFixedTableMap::COL_I04, $this->i04);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I05)) {
            $criteria->add(IntFixedTableMap::COL_I05, $this->i05);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I06)) {
            $criteria->add(IntFixedTableMap::COL_I06, $this->i06);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I07)) {
            $criteria->add(IntFixedTableMap::COL_I07, $this->i07);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I08)) {
            $criteria->add(IntFixedTableMap::COL_I08, $this->i08);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I09)) {
            $criteria->add(IntFixedTableMap::COL_I09, $this->i09);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I10)) {
            $criteria->add(IntFixedTableMap::COL_I10, $this->i10);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I11)) {
            $criteria->add(IntFixedTableMap::COL_I11, $this->i11);
        }
        if ($this->isColumnModified(IntFixedTableMap::COL_I12)) {
            $criteria->add(IntFixedTableMap::COL_I12, $this->i12);
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
        $criteria = ChildIntFixedQuery::create();
        $criteria->add(IntFixedTableMap::COL_INT_FIXED_ID, $this->int_fixed_id);

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
        $validPk = null !== $this->getIntFixedId();

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
        return $this->getIntFixedId();
    }

    /**
     * Generic method to set the primary key (int_fixed_id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setIntFixedId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getIntFixedId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \ThePHPBench\Propel2\IntFixed (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setI01($this->getI01());
        $copyObj->setI02($this->getI02());
        $copyObj->setI03($this->getI03());
        $copyObj->setI04($this->getI04());
        $copyObj->setI05($this->getI05());
        $copyObj->setI06($this->getI06());
        $copyObj->setI07($this->getI07());
        $copyObj->setI08($this->getI08());
        $copyObj->setI09($this->getI09());
        $copyObj->setI10($this->getI10());
        $copyObj->setI11($this->getI11());
        $copyObj->setI12($this->getI12());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setIntFixedId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \ThePHPBench\Propel2\IntFixed Clone of current object.
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
        $this->int_fixed_id = null;
        $this->i01 = null;
        $this->i02 = null;
        $this->i03 = null;
        $this->i04 = null;
        $this->i05 = null;
        $this->i06 = null;
        $this->i07 = null;
        $this->i08 = null;
        $this->i09 = null;
        $this->i10 = null;
        $this->i11 = null;
        $this->i12 = null;
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
        return (string) $this->exportTo(IntFixedTableMap::DEFAULT_STRING_FORMAT);
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
