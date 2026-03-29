<?php

namespace ThePHPBench\Propel2\Base;

use \DateTime;
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
use Propel\Runtime\Util\PropelDateTime;
use ThePHPBench\Propel2\CanaryQuery as ChildCanaryQuery;
use ThePHPBench\Propel2\Map\CanaryTableMap;

/**
 * Base class that represents a row from the 'canaries' table.
 *
 *
 *
 * @package    propel.generator.ThePHPBench.Propel2.Base
 */
abstract class Canary implements ActiveRecordInterface
{
    /**
     * TableMap class name
     *
     * @var string
     */
    public const TABLE_MAP = '\\ThePHPBench\\Propel2\\Map\\CanaryTableMap';


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
     * The value for the canary_id field.
     *
     * @var        int
     */
    protected $canary_id;

    /**
     * The value for the bool_flag field.
     *
     * @var        boolean
     */
    protected $bool_flag;

    /**
     * The value for the small_int field.
     *
     * @var        int
     */
    protected $small_int;

    /**
     * The value for the int_value field.
     *
     * @var        int
     */
    protected $int_value;

    /**
     * The value for the big_int field.
     *
     * @var        string
     */
    protected $big_int;

    /**
     * The value for the float_value field.
     *
     * @var        double
     */
    protected $float_value;

    /**
     * The value for the double_value field.
     *
     * @var        double
     */
    protected $double_value;

    /**
     * The value for the decimal_value field.
     *
     * @var        string
     */
    protected $decimal_value;

    /**
     * The value for the fixed_char_8 field.
     *
     * @var        string
     */
    protected $fixed_char_8;

    /**
     * The value for the fixed_char_16 field.
     *
     * @var        string
     */
    protected $fixed_char_16;

    /**
     * The value for the string_short field.
     *
     * @var        string
     */
    protected $string_short;

    /**
     * The value for the string_medium field.
     *
     * @var        string
     */
    protected $string_medium;

    /**
     * The value for the string_long field.
     *
     * @var        string
     */
    protected $string_long;

    /**
     * The value for the text_value field.
     *
     * @var        string
     */
    protected $text_value;

    /**
     * The value for the date_value field.
     *
     * @var        DateTime
     */
    protected $date_value;

    /**
     * The value for the datetime_value field.
     *
     * @var        DateTime|null
     */
    protected $datetime_value;

    /**
     * The value for the nullable_string field.
     *
     * @var        string|null
     */
    protected $nullable_string;

    /**
     * The value for the nullable_int field.
     *
     * @var        int|null
     */
    protected $nullable_int;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var bool
     */
    protected $alreadyInSave = false;

    /**
     * Initializes internal state of ThePHPBench\Propel2\Base\Canary object.
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
     * Compares this with another <code>Canary</code> instance.  If
     * <code>obj</code> is an instance of <code>Canary</code>, delegates to
     * <code>equals(Canary)</code>.  Otherwise, returns <code>false</code>.
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
     * Get the [canary_id] column value.
     *
     * @return int
     */
    public function getCanaryId()
    {
        return $this->canary_id;
    }

    /**
     * Get the [bool_flag] column value.
     *
     * @return boolean
     */
    public function getBoolFlag()
    {
        return $this->bool_flag;
    }

    /**
     * Get the [bool_flag] column value.
     *
     * @return boolean
     */
    public function isBoolFlag()
    {
        return $this->getBoolFlag();
    }

    /**
     * Get the [small_int] column value.
     *
     * @return int
     */
    public function getSmallInt()
    {
        return $this->small_int;
    }

    /**
     * Get the [int_value] column value.
     *
     * @return int
     */
    public function getIntValue()
    {
        return $this->int_value;
    }

    /**
     * Get the [big_int] column value.
     *
     * @return string
     */
    public function getBigInt()
    {
        return $this->big_int;
    }

    /**
     * Get the [float_value] column value.
     *
     * @return double
     */
    public function getFloatValue()
    {
        return $this->float_value;
    }

    /**
     * Get the [double_value] column value.
     *
     * @return double
     */
    public function getDoubleValue()
    {
        return $this->double_value;
    }

    /**
     * Get the [decimal_value] column value.
     *
     * @return string
     */
    public function getDecimalValue()
    {
        return $this->decimal_value;
    }

    /**
     * Get the [fixed_char_8] column value.
     *
     * @return string
     */
    public function getFixedChar8()
    {
        return $this->fixed_char_8;
    }

    /**
     * Get the [fixed_char_16] column value.
     *
     * @return string
     */
    public function getFixedChar16()
    {
        return $this->fixed_char_16;
    }

    /**
     * Get the [string_short] column value.
     *
     * @return string
     */
    public function getStringShort()
    {
        return $this->string_short;
    }

    /**
     * Get the [string_medium] column value.
     *
     * @return string
     */
    public function getStringMedium()
    {
        return $this->string_medium;
    }

    /**
     * Get the [string_long] column value.
     *
     * @return string
     */
    public function getStringLong()
    {
        return $this->string_long;
    }

    /**
     * Get the [text_value] column value.
     *
     * @return string
     */
    public function getTextValue()
    {
        return $this->text_value;
    }

    /**
     * Get the [optionally formatted] temporal [date_value] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL).
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime : string)
     */
    public function getDateValue(?string $format = null)
    {
        if ($format === null) {
            return $this->date_value;
        } else {
            return $this->date_value instanceof \DateTimeInterface ? $this->date_value->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [datetime_value] column value.
     *
     *
     * @param string|null $format The date/time format string (either date()-style or strftime()-style).
     *   If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime|null Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL.
     *
     * @throws \Propel\Runtime\Exception\PropelException - if unable to parse/validate the date/time value.
     *
     * @psalm-return ($format is null ? DateTime|null : string|null)
     */
    public function getDatetimeValue(?string $format = null)
    {
        if ($format === null) {
            return $this->datetime_value;
        } else {
            return $this->datetime_value instanceof \DateTimeInterface ? $this->datetime_value->format($format) : null;
        }
    }

    /**
     * Get the [nullable_string] column value.
     *
     * @return string|null
     */
    public function getNullableString()
    {
        return $this->nullable_string;
    }

    /**
     * Get the [nullable_int] column value.
     *
     * @return int|null
     */
    public function getNullableInt()
    {
        return $this->nullable_int;
    }

    /**
     * Set the value of [canary_id] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setCanaryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->canary_id !== $v) {
            $this->canary_id = $v;
            $this->modifiedColumns[CanaryTableMap::COL_CANARY_ID] = true;
        }

        return $this;
    }

    /**
     * Sets the value of the [bool_flag] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param bool|integer|string $v The new value
     * @return $this The current object (for fluent API support)
     */
    public function setBoolFlag($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->bool_flag !== $v) {
            $this->bool_flag = $v;
            $this->modifiedColumns[CanaryTableMap::COL_BOOL_FLAG] = true;
        }

        return $this;
    }

    /**
     * Set the value of [small_int] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setSmallInt($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->small_int !== $v) {
            $this->small_int = $v;
            $this->modifiedColumns[CanaryTableMap::COL_SMALL_INT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [int_value] column.
     *
     * @param int $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setIntValue($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->int_value !== $v) {
            $this->int_value = $v;
            $this->modifiedColumns[CanaryTableMap::COL_INT_VALUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [big_int] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setBigInt($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->big_int !== $v) {
            $this->big_int = $v;
            $this->modifiedColumns[CanaryTableMap::COL_BIG_INT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [float_value] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFloatValue($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->float_value !== $v) {
            $this->float_value = $v;
            $this->modifiedColumns[CanaryTableMap::COL_FLOAT_VALUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [double_value] column.
     *
     * @param double $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDoubleValue($v)
    {
        if ($v !== null) {
            $v = (double) $v;
        }

        if ($this->double_value !== $v) {
            $this->double_value = $v;
            $this->modifiedColumns[CanaryTableMap::COL_DOUBLE_VALUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [decimal_value] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setDecimalValue($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->decimal_value !== $v) {
            $this->decimal_value = $v;
            $this->modifiedColumns[CanaryTableMap::COL_DECIMAL_VALUE] = true;
        }

        return $this;
    }

    /**
     * Set the value of [fixed_char_8] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFixedChar8($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fixed_char_8 !== $v) {
            $this->fixed_char_8 = $v;
            $this->modifiedColumns[CanaryTableMap::COL_FIXED_CHAR_8] = true;
        }

        return $this;
    }

    /**
     * Set the value of [fixed_char_16] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setFixedChar16($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->fixed_char_16 !== $v) {
            $this->fixed_char_16 = $v;
            $this->modifiedColumns[CanaryTableMap::COL_FIXED_CHAR_16] = true;
        }

        return $this;
    }

    /**
     * Set the value of [string_short] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStringShort($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->string_short !== $v) {
            $this->string_short = $v;
            $this->modifiedColumns[CanaryTableMap::COL_STRING_SHORT] = true;
        }

        return $this;
    }

    /**
     * Set the value of [string_medium] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStringMedium($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->string_medium !== $v) {
            $this->string_medium = $v;
            $this->modifiedColumns[CanaryTableMap::COL_STRING_MEDIUM] = true;
        }

        return $this;
    }

    /**
     * Set the value of [string_long] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setStringLong($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->string_long !== $v) {
            $this->string_long = $v;
            $this->modifiedColumns[CanaryTableMap::COL_STRING_LONG] = true;
        }

        return $this;
    }

    /**
     * Set the value of [text_value] column.
     *
     * @param string $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setTextValue($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->text_value !== $v) {
            $this->text_value = $v;
            $this->modifiedColumns[CanaryTableMap::COL_TEXT_VALUE] = true;
        }

        return $this;
    }

    /**
     * Sets the value of [date_value] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDateValue($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date_value !== null || $dt !== null) {
            if ($this->date_value === null || $dt === null || $dt->format("Y-m-d") !== $this->date_value->format("Y-m-d")) {
                $this->date_value = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CanaryTableMap::COL_DATE_VALUE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Sets the value of [datetime_value] column to a normalized version of the date/time value specified.
     *
     * @param string|integer|\DateTimeInterface|null $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this The current object (for fluent API support)
     */
    public function setDatetimeValue($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->datetime_value !== null || $dt !== null) {
            if ($this->datetime_value === null || $dt === null || $dt->format("Y-m-d H:i:s.u") !== $this->datetime_value->format("Y-m-d H:i:s.u")) {
                $this->datetime_value = $dt === null ? null : clone $dt;
                $this->modifiedColumns[CanaryTableMap::COL_DATETIME_VALUE] = true;
            }
        } // if either are not null

        return $this;
    }

    /**
     * Set the value of [nullable_string] column.
     *
     * @param string|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNullableString($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nullable_string !== $v) {
            $this->nullable_string = $v;
            $this->modifiedColumns[CanaryTableMap::COL_NULLABLE_STRING] = true;
        }

        return $this;
    }

    /**
     * Set the value of [nullable_int] column.
     *
     * @param int|null $v New value
     * @return $this The current object (for fluent API support)
     */
    public function setNullableInt($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->nullable_int !== $v) {
            $this->nullable_int = $v;
            $this->modifiedColumns[CanaryTableMap::COL_NULLABLE_INT] = true;
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CanaryTableMap::translateFieldName('CanaryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->canary_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CanaryTableMap::translateFieldName('BoolFlag', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bool_flag = (null !== $col) ? (boolean) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CanaryTableMap::translateFieldName('SmallInt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->small_int = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : CanaryTableMap::translateFieldName('IntValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->int_value = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : CanaryTableMap::translateFieldName('BigInt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->big_int = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : CanaryTableMap::translateFieldName('FloatValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->float_value = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : CanaryTableMap::translateFieldName('DoubleValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->double_value = (null !== $col) ? (double) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : CanaryTableMap::translateFieldName('DecimalValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->decimal_value = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : CanaryTableMap::translateFieldName('FixedChar8', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fixed_char_8 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : CanaryTableMap::translateFieldName('FixedChar16', TableMap::TYPE_PHPNAME, $indexType)];
            $this->fixed_char_16 = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : CanaryTableMap::translateFieldName('StringShort', TableMap::TYPE_PHPNAME, $indexType)];
            $this->string_short = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : CanaryTableMap::translateFieldName('StringMedium', TableMap::TYPE_PHPNAME, $indexType)];
            $this->string_medium = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : CanaryTableMap::translateFieldName('StringLong', TableMap::TYPE_PHPNAME, $indexType)];
            $this->string_long = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : CanaryTableMap::translateFieldName('TextValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->text_value = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 14 + $startcol : CanaryTableMap::translateFieldName('DateValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->date_value = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 15 + $startcol : CanaryTableMap::translateFieldName('DatetimeValue', TableMap::TYPE_PHPNAME, $indexType)];
            $this->datetime_value = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 16 + $startcol : CanaryTableMap::translateFieldName('NullableString', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nullable_string = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 17 + $startcol : CanaryTableMap::translateFieldName('NullableInt', TableMap::TYPE_PHPNAME, $indexType)];
            $this->nullable_int = (null !== $col) ? (int) $col : null;

            $this->resetModified();
            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 18; // 18 = CanaryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\ThePHPBench\\Propel2\\Canary'), 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(CanaryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCanaryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
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
     * @see Canary::setDeleted()
     * @see Canary::isDeleted()
     */
    public function delete(?ConnectionInterface $con = null): void
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCanaryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
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
                CanaryTableMap::addInstanceToPool($this);
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

        if (null === $this->canary_id) {
            throw new PropelException('Cannot insert Canary without a primary key value.');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CanaryTableMap::COL_CANARY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'canary_id';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_BOOL_FLAG)) {
            $modifiedColumns[':p' . $index++]  = 'bool_flag';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_SMALL_INT)) {
            $modifiedColumns[':p' . $index++]  = 'small_int';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_INT_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'int_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_BIG_INT)) {
            $modifiedColumns[':p' . $index++]  = 'big_int';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FLOAT_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'float_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DOUBLE_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'double_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DECIMAL_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'decimal_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FIXED_CHAR_8)) {
            $modifiedColumns[':p' . $index++]  = 'fixed_char_8';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FIXED_CHAR_16)) {
            $modifiedColumns[':p' . $index++]  = 'fixed_char_16';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_SHORT)) {
            $modifiedColumns[':p' . $index++]  = 'string_short';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_MEDIUM)) {
            $modifiedColumns[':p' . $index++]  = 'string_medium';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_LONG)) {
            $modifiedColumns[':p' . $index++]  = 'string_long';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_TEXT_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'text_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DATE_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'date_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DATETIME_VALUE)) {
            $modifiedColumns[':p' . $index++]  = 'datetime_value';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_NULLABLE_STRING)) {
            $modifiedColumns[':p' . $index++]  = 'nullable_string';
        }
        if ($this->isColumnModified(CanaryTableMap::COL_NULLABLE_INT)) {
            $modifiedColumns[':p' . $index++]  = 'nullable_int';
        }

        $sql = sprintf(
            'INSERT INTO canaries (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'canary_id':
                        $stmt->bindValue($identifier, $this->canary_id, PDO::PARAM_INT);

                        break;
                    case 'bool_flag':
                        $stmt->bindValue($identifier, $this->bool_flag, PDO::PARAM_BOOL);

                        break;
                    case 'small_int':
                        $stmt->bindValue($identifier, $this->small_int, PDO::PARAM_INT);

                        break;
                    case 'int_value':
                        $stmt->bindValue($identifier, $this->int_value, PDO::PARAM_INT);

                        break;
                    case 'big_int':
                        $stmt->bindValue($identifier, $this->big_int, PDO::PARAM_INT);

                        break;
                    case 'float_value':
                        $stmt->bindValue($identifier, $this->float_value, PDO::PARAM_STR);

                        break;
                    case 'double_value':
                        $stmt->bindValue($identifier, $this->double_value, PDO::PARAM_STR);

                        break;
                    case 'decimal_value':
                        $stmt->bindValue($identifier, $this->decimal_value, PDO::PARAM_STR);

                        break;
                    case 'fixed_char_8':
                        $stmt->bindValue($identifier, $this->fixed_char_8, PDO::PARAM_STR);

                        break;
                    case 'fixed_char_16':
                        $stmt->bindValue($identifier, $this->fixed_char_16, PDO::PARAM_STR);

                        break;
                    case 'string_short':
                        $stmt->bindValue($identifier, $this->string_short, PDO::PARAM_STR);

                        break;
                    case 'string_medium':
                        $stmt->bindValue($identifier, $this->string_medium, PDO::PARAM_STR);

                        break;
                    case 'string_long':
                        $stmt->bindValue($identifier, $this->string_long, PDO::PARAM_STR);

                        break;
                    case 'text_value':
                        $stmt->bindValue($identifier, $this->text_value, PDO::PARAM_STR);

                        break;
                    case 'date_value':
                        $stmt->bindValue($identifier, $this->date_value ? $this->date_value->format("Y-m-d") : null, PDO::PARAM_STR);

                        break;
                    case 'datetime_value':
                        $stmt->bindValue($identifier, $this->datetime_value ? $this->datetime_value->format("Y-m-d H:i:s.u") : null, PDO::PARAM_STR);

                        break;
                    case 'nullable_string':
                        $stmt->bindValue($identifier, $this->nullable_string, PDO::PARAM_STR);

                        break;
                    case 'nullable_int':
                        $stmt->bindValue($identifier, $this->nullable_int, PDO::PARAM_INT);

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
        $pos = CanaryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCanaryId();

            case 1:
                return $this->getBoolFlag();

            case 2:
                return $this->getSmallInt();

            case 3:
                return $this->getIntValue();

            case 4:
                return $this->getBigInt();

            case 5:
                return $this->getFloatValue();

            case 6:
                return $this->getDoubleValue();

            case 7:
                return $this->getDecimalValue();

            case 8:
                return $this->getFixedChar8();

            case 9:
                return $this->getFixedChar16();

            case 10:
                return $this->getStringShort();

            case 11:
                return $this->getStringMedium();

            case 12:
                return $this->getStringLong();

            case 13:
                return $this->getTextValue();

            case 14:
                return $this->getDateValue();

            case 15:
                return $this->getDatetimeValue();

            case 16:
                return $this->getNullableString();

            case 17:
                return $this->getNullableInt();

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
        if (isset($alreadyDumpedObjects['Canary'][$this->hashCode()])) {
            return ['*RECURSION*'];
        }
        $alreadyDumpedObjects['Canary'][$this->hashCode()] = true;
        $keys = CanaryTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getCanaryId(),
            $keys[1] => $this->getBoolFlag(),
            $keys[2] => $this->getSmallInt(),
            $keys[3] => $this->getIntValue(),
            $keys[4] => $this->getBigInt(),
            $keys[5] => $this->getFloatValue(),
            $keys[6] => $this->getDoubleValue(),
            $keys[7] => $this->getDecimalValue(),
            $keys[8] => $this->getFixedChar8(),
            $keys[9] => $this->getFixedChar16(),
            $keys[10] => $this->getStringShort(),
            $keys[11] => $this->getStringMedium(),
            $keys[12] => $this->getStringLong(),
            $keys[13] => $this->getTextValue(),
            $keys[14] => $this->getDateValue(),
            $keys[15] => $this->getDatetimeValue(),
            $keys[16] => $this->getNullableString(),
            $keys[17] => $this->getNullableInt(),
        ];
        if ($result[$keys[14]] instanceof \DateTimeInterface) {
            $result[$keys[14]] = $result[$keys[14]]->format('Y-m-d');
        }

        if ($result[$keys[15]] instanceof \DateTimeInterface) {
            $result[$keys[15]] = $result[$keys[15]]->format('Y-m-d H:i:s.u');
        }

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
        $pos = CanaryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCanaryId($value);
                break;
            case 1:
                $this->setBoolFlag($value);
                break;
            case 2:
                $this->setSmallInt($value);
                break;
            case 3:
                $this->setIntValue($value);
                break;
            case 4:
                $this->setBigInt($value);
                break;
            case 5:
                $this->setFloatValue($value);
                break;
            case 6:
                $this->setDoubleValue($value);
                break;
            case 7:
                $this->setDecimalValue($value);
                break;
            case 8:
                $this->setFixedChar8($value);
                break;
            case 9:
                $this->setFixedChar16($value);
                break;
            case 10:
                $this->setStringShort($value);
                break;
            case 11:
                $this->setStringMedium($value);
                break;
            case 12:
                $this->setStringLong($value);
                break;
            case 13:
                $this->setTextValue($value);
                break;
            case 14:
                $this->setDateValue($value);
                break;
            case 15:
                $this->setDatetimeValue($value);
                break;
            case 16:
                $this->setNullableString($value);
                break;
            case 17:
                $this->setNullableInt($value);
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
        $keys = CanaryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setCanaryId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setBoolFlag($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setSmallInt($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setIntValue($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setBigInt($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setFloatValue($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setDoubleValue($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setDecimalValue($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setFixedChar8($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setFixedChar16($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setStringShort($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setStringMedium($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setStringLong($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setTextValue($arr[$keys[13]]);
        }
        if (array_key_exists($keys[14], $arr)) {
            $this->setDateValue($arr[$keys[14]]);
        }
        if (array_key_exists($keys[15], $arr)) {
            $this->setDatetimeValue($arr[$keys[15]]);
        }
        if (array_key_exists($keys[16], $arr)) {
            $this->setNullableString($arr[$keys[16]]);
        }
        if (array_key_exists($keys[17], $arr)) {
            $this->setNullableInt($arr[$keys[17]]);
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
        $criteria = new Criteria(CanaryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CanaryTableMap::COL_CANARY_ID)) {
            $criteria->add(CanaryTableMap::COL_CANARY_ID, $this->canary_id);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_BOOL_FLAG)) {
            $criteria->add(CanaryTableMap::COL_BOOL_FLAG, $this->bool_flag);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_SMALL_INT)) {
            $criteria->add(CanaryTableMap::COL_SMALL_INT, $this->small_int);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_INT_VALUE)) {
            $criteria->add(CanaryTableMap::COL_INT_VALUE, $this->int_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_BIG_INT)) {
            $criteria->add(CanaryTableMap::COL_BIG_INT, $this->big_int);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FLOAT_VALUE)) {
            $criteria->add(CanaryTableMap::COL_FLOAT_VALUE, $this->float_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DOUBLE_VALUE)) {
            $criteria->add(CanaryTableMap::COL_DOUBLE_VALUE, $this->double_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DECIMAL_VALUE)) {
            $criteria->add(CanaryTableMap::COL_DECIMAL_VALUE, $this->decimal_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FIXED_CHAR_8)) {
            $criteria->add(CanaryTableMap::COL_FIXED_CHAR_8, $this->fixed_char_8);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_FIXED_CHAR_16)) {
            $criteria->add(CanaryTableMap::COL_FIXED_CHAR_16, $this->fixed_char_16);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_SHORT)) {
            $criteria->add(CanaryTableMap::COL_STRING_SHORT, $this->string_short);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_MEDIUM)) {
            $criteria->add(CanaryTableMap::COL_STRING_MEDIUM, $this->string_medium);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_STRING_LONG)) {
            $criteria->add(CanaryTableMap::COL_STRING_LONG, $this->string_long);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_TEXT_VALUE)) {
            $criteria->add(CanaryTableMap::COL_TEXT_VALUE, $this->text_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DATE_VALUE)) {
            $criteria->add(CanaryTableMap::COL_DATE_VALUE, $this->date_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_DATETIME_VALUE)) {
            $criteria->add(CanaryTableMap::COL_DATETIME_VALUE, $this->datetime_value);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_NULLABLE_STRING)) {
            $criteria->add(CanaryTableMap::COL_NULLABLE_STRING, $this->nullable_string);
        }
        if ($this->isColumnModified(CanaryTableMap::COL_NULLABLE_INT)) {
            $criteria->add(CanaryTableMap::COL_NULLABLE_INT, $this->nullable_int);
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
        $criteria = ChildCanaryQuery::create();
        $criteria->add(CanaryTableMap::COL_CANARY_ID, $this->canary_id);

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
        $validPk = null !== $this->getCanaryId();

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
        return $this->getCanaryId();
    }

    /**
     * Generic method to set the primary key (canary_id column).
     *
     * @param int|null $key Primary key.
     * @return void
     */
    public function setPrimaryKey(?int $key = null): void
    {
        $this->setCanaryId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return bool
     */
    public function isPrimaryKeyNull(): bool
    {
        return null === $this->getCanaryId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of \ThePHPBench\Propel2\Canary (or compatible) type.
     * @param bool $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param bool $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws \Propel\Runtime\Exception\PropelException
     * @return void
     */
    public function copyInto(object $copyObj, bool $deepCopy = false, bool $makeNew = true): void
    {
        $copyObj->setBoolFlag($this->getBoolFlag());
        $copyObj->setSmallInt($this->getSmallInt());
        $copyObj->setIntValue($this->getIntValue());
        $copyObj->setBigInt($this->getBigInt());
        $copyObj->setFloatValue($this->getFloatValue());
        $copyObj->setDoubleValue($this->getDoubleValue());
        $copyObj->setDecimalValue($this->getDecimalValue());
        $copyObj->setFixedChar8($this->getFixedChar8());
        $copyObj->setFixedChar16($this->getFixedChar16());
        $copyObj->setStringShort($this->getStringShort());
        $copyObj->setStringMedium($this->getStringMedium());
        $copyObj->setStringLong($this->getStringLong());
        $copyObj->setTextValue($this->getTextValue());
        $copyObj->setDateValue($this->getDateValue());
        $copyObj->setDatetimeValue($this->getDatetimeValue());
        $copyObj->setNullableString($this->getNullableString());
        $copyObj->setNullableInt($this->getNullableInt());
        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setCanaryId(NULL); // this is a auto-increment column, so set to default value
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
     * @return \ThePHPBench\Propel2\Canary Clone of current object.
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
        $this->canary_id = null;
        $this->bool_flag = null;
        $this->small_int = null;
        $this->int_value = null;
        $this->big_int = null;
        $this->float_value = null;
        $this->double_value = null;
        $this->decimal_value = null;
        $this->fixed_char_8 = null;
        $this->fixed_char_16 = null;
        $this->string_short = null;
        $this->string_medium = null;
        $this->string_long = null;
        $this->text_value = null;
        $this->date_value = null;
        $this->datetime_value = null;
        $this->nullable_string = null;
        $this->nullable_int = null;
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
        return (string) $this->exportTo(CanaryTableMap::DEFAULT_STRING_FORMAT);
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
