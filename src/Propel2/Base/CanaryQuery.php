<?php

namespace ThePHPBench\Propel2\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use ThePHPBench\Propel2\Canary as ChildCanary;
use ThePHPBench\Propel2\CanaryQuery as ChildCanaryQuery;
use ThePHPBench\Propel2\Map\CanaryTableMap;

/**
 * Base class that represents a query for the `canaries` table.
 *
 * @method     ChildCanaryQuery orderByCanaryId($order = Criteria::ASC) Order by the canary_id column
 * @method     ChildCanaryQuery orderByBoolFlag($order = Criteria::ASC) Order by the bool_flag column
 * @method     ChildCanaryQuery orderBySmallInt($order = Criteria::ASC) Order by the small_int column
 * @method     ChildCanaryQuery orderByIntValue($order = Criteria::ASC) Order by the int_value column
 * @method     ChildCanaryQuery orderByBigInt($order = Criteria::ASC) Order by the big_int column
 * @method     ChildCanaryQuery orderByFloatValue($order = Criteria::ASC) Order by the float_value column
 * @method     ChildCanaryQuery orderByDoubleValue($order = Criteria::ASC) Order by the double_value column
 * @method     ChildCanaryQuery orderByDecimalValue($order = Criteria::ASC) Order by the decimal_value column
 * @method     ChildCanaryQuery orderByFixedChar8($order = Criteria::ASC) Order by the fixed_char_8 column
 * @method     ChildCanaryQuery orderByFixedChar16($order = Criteria::ASC) Order by the fixed_char_16 column
 * @method     ChildCanaryQuery orderByStringShort($order = Criteria::ASC) Order by the string_short column
 * @method     ChildCanaryQuery orderByStringMedium($order = Criteria::ASC) Order by the string_medium column
 * @method     ChildCanaryQuery orderByStringLong($order = Criteria::ASC) Order by the string_long column
 * @method     ChildCanaryQuery orderByTextValue($order = Criteria::ASC) Order by the text_value column
 * @method     ChildCanaryQuery orderByDateValue($order = Criteria::ASC) Order by the date_value column
 * @method     ChildCanaryQuery orderByDatetimeValue($order = Criteria::ASC) Order by the datetime_value column
 * @method     ChildCanaryQuery orderByNullableString($order = Criteria::ASC) Order by the nullable_string column
 * @method     ChildCanaryQuery orderByNullableInt($order = Criteria::ASC) Order by the nullable_int column
 *
 * @method     ChildCanaryQuery groupByCanaryId() Group by the canary_id column
 * @method     ChildCanaryQuery groupByBoolFlag() Group by the bool_flag column
 * @method     ChildCanaryQuery groupBySmallInt() Group by the small_int column
 * @method     ChildCanaryQuery groupByIntValue() Group by the int_value column
 * @method     ChildCanaryQuery groupByBigInt() Group by the big_int column
 * @method     ChildCanaryQuery groupByFloatValue() Group by the float_value column
 * @method     ChildCanaryQuery groupByDoubleValue() Group by the double_value column
 * @method     ChildCanaryQuery groupByDecimalValue() Group by the decimal_value column
 * @method     ChildCanaryQuery groupByFixedChar8() Group by the fixed_char_8 column
 * @method     ChildCanaryQuery groupByFixedChar16() Group by the fixed_char_16 column
 * @method     ChildCanaryQuery groupByStringShort() Group by the string_short column
 * @method     ChildCanaryQuery groupByStringMedium() Group by the string_medium column
 * @method     ChildCanaryQuery groupByStringLong() Group by the string_long column
 * @method     ChildCanaryQuery groupByTextValue() Group by the text_value column
 * @method     ChildCanaryQuery groupByDateValue() Group by the date_value column
 * @method     ChildCanaryQuery groupByDatetimeValue() Group by the datetime_value column
 * @method     ChildCanaryQuery groupByNullableString() Group by the nullable_string column
 * @method     ChildCanaryQuery groupByNullableInt() Group by the nullable_int column
 *
 * @method     ChildCanaryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildCanaryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildCanaryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildCanaryQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildCanaryQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildCanaryQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildCanary|null findOne(?ConnectionInterface $con = null) Return the first ChildCanary matching the query
 * @method     ChildCanary findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildCanary matching the query, or a new ChildCanary object populated from the query conditions when no match is found
 *
 * @method     ChildCanary|null findOneByCanaryId(int $canary_id) Return the first ChildCanary filtered by the canary_id column
 * @method     ChildCanary|null findOneByBoolFlag(boolean $bool_flag) Return the first ChildCanary filtered by the bool_flag column
 * @method     ChildCanary|null findOneBySmallInt(int $small_int) Return the first ChildCanary filtered by the small_int column
 * @method     ChildCanary|null findOneByIntValue(int $int_value) Return the first ChildCanary filtered by the int_value column
 * @method     ChildCanary|null findOneByBigInt(string $big_int) Return the first ChildCanary filtered by the big_int column
 * @method     ChildCanary|null findOneByFloatValue(double $float_value) Return the first ChildCanary filtered by the float_value column
 * @method     ChildCanary|null findOneByDoubleValue(double $double_value) Return the first ChildCanary filtered by the double_value column
 * @method     ChildCanary|null findOneByDecimalValue(string $decimal_value) Return the first ChildCanary filtered by the decimal_value column
 * @method     ChildCanary|null findOneByFixedChar8(string $fixed_char_8) Return the first ChildCanary filtered by the fixed_char_8 column
 * @method     ChildCanary|null findOneByFixedChar16(string $fixed_char_16) Return the first ChildCanary filtered by the fixed_char_16 column
 * @method     ChildCanary|null findOneByStringShort(string $string_short) Return the first ChildCanary filtered by the string_short column
 * @method     ChildCanary|null findOneByStringMedium(string $string_medium) Return the first ChildCanary filtered by the string_medium column
 * @method     ChildCanary|null findOneByStringLong(string $string_long) Return the first ChildCanary filtered by the string_long column
 * @method     ChildCanary|null findOneByTextValue(string $text_value) Return the first ChildCanary filtered by the text_value column
 * @method     ChildCanary|null findOneByDateValue(string $date_value) Return the first ChildCanary filtered by the date_value column
 * @method     ChildCanary|null findOneByDatetimeValue(string $datetime_value) Return the first ChildCanary filtered by the datetime_value column
 * @method     ChildCanary|null findOneByNullableString(string $nullable_string) Return the first ChildCanary filtered by the nullable_string column
 * @method     ChildCanary|null findOneByNullableInt(int $nullable_int) Return the first ChildCanary filtered by the nullable_int column
 *
 * @method     ChildCanary requirePk($key, ?ConnectionInterface $con = null) Return the ChildCanary by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOne(?ConnectionInterface $con = null) Return the first ChildCanary matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCanary requireOneByCanaryId(int $canary_id) Return the first ChildCanary filtered by the canary_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByBoolFlag(boolean $bool_flag) Return the first ChildCanary filtered by the bool_flag column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneBySmallInt(int $small_int) Return the first ChildCanary filtered by the small_int column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByIntValue(int $int_value) Return the first ChildCanary filtered by the int_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByBigInt(string $big_int) Return the first ChildCanary filtered by the big_int column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByFloatValue(double $float_value) Return the first ChildCanary filtered by the float_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByDoubleValue(double $double_value) Return the first ChildCanary filtered by the double_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByDecimalValue(string $decimal_value) Return the first ChildCanary filtered by the decimal_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByFixedChar8(string $fixed_char_8) Return the first ChildCanary filtered by the fixed_char_8 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByFixedChar16(string $fixed_char_16) Return the first ChildCanary filtered by the fixed_char_16 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByStringShort(string $string_short) Return the first ChildCanary filtered by the string_short column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByStringMedium(string $string_medium) Return the first ChildCanary filtered by the string_medium column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByStringLong(string $string_long) Return the first ChildCanary filtered by the string_long column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByTextValue(string $text_value) Return the first ChildCanary filtered by the text_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByDateValue(string $date_value) Return the first ChildCanary filtered by the date_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByDatetimeValue(string $datetime_value) Return the first ChildCanary filtered by the datetime_value column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByNullableString(string $nullable_string) Return the first ChildCanary filtered by the nullable_string column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildCanary requireOneByNullableInt(int $nullable_int) Return the first ChildCanary filtered by the nullable_int column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildCanary[]|Collection find(?ConnectionInterface $con = null) Return ChildCanary objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildCanary> find(?ConnectionInterface $con = null) Return ChildCanary objects based on current ModelCriteria
 *
 * @method     ChildCanary[]|Collection findByCanaryId(int|array<int> $canary_id) Return ChildCanary objects filtered by the canary_id column
 * @psalm-method Collection&\Traversable<ChildCanary> findByCanaryId(int|array<int> $canary_id) Return ChildCanary objects filtered by the canary_id column
 * @method     ChildCanary[]|Collection findByBoolFlag(boolean|array<boolean> $bool_flag) Return ChildCanary objects filtered by the bool_flag column
 * @psalm-method Collection&\Traversable<ChildCanary> findByBoolFlag(boolean|array<boolean> $bool_flag) Return ChildCanary objects filtered by the bool_flag column
 * @method     ChildCanary[]|Collection findBySmallInt(int|array<int> $small_int) Return ChildCanary objects filtered by the small_int column
 * @psalm-method Collection&\Traversable<ChildCanary> findBySmallInt(int|array<int> $small_int) Return ChildCanary objects filtered by the small_int column
 * @method     ChildCanary[]|Collection findByIntValue(int|array<int> $int_value) Return ChildCanary objects filtered by the int_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByIntValue(int|array<int> $int_value) Return ChildCanary objects filtered by the int_value column
 * @method     ChildCanary[]|Collection findByBigInt(string|array<string> $big_int) Return ChildCanary objects filtered by the big_int column
 * @psalm-method Collection&\Traversable<ChildCanary> findByBigInt(string|array<string> $big_int) Return ChildCanary objects filtered by the big_int column
 * @method     ChildCanary[]|Collection findByFloatValue(double|array<double> $float_value) Return ChildCanary objects filtered by the float_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByFloatValue(double|array<double> $float_value) Return ChildCanary objects filtered by the float_value column
 * @method     ChildCanary[]|Collection findByDoubleValue(double|array<double> $double_value) Return ChildCanary objects filtered by the double_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByDoubleValue(double|array<double> $double_value) Return ChildCanary objects filtered by the double_value column
 * @method     ChildCanary[]|Collection findByDecimalValue(string|array<string> $decimal_value) Return ChildCanary objects filtered by the decimal_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByDecimalValue(string|array<string> $decimal_value) Return ChildCanary objects filtered by the decimal_value column
 * @method     ChildCanary[]|Collection findByFixedChar8(string|array<string> $fixed_char_8) Return ChildCanary objects filtered by the fixed_char_8 column
 * @psalm-method Collection&\Traversable<ChildCanary> findByFixedChar8(string|array<string> $fixed_char_8) Return ChildCanary objects filtered by the fixed_char_8 column
 * @method     ChildCanary[]|Collection findByFixedChar16(string|array<string> $fixed_char_16) Return ChildCanary objects filtered by the fixed_char_16 column
 * @psalm-method Collection&\Traversable<ChildCanary> findByFixedChar16(string|array<string> $fixed_char_16) Return ChildCanary objects filtered by the fixed_char_16 column
 * @method     ChildCanary[]|Collection findByStringShort(string|array<string> $string_short) Return ChildCanary objects filtered by the string_short column
 * @psalm-method Collection&\Traversable<ChildCanary> findByStringShort(string|array<string> $string_short) Return ChildCanary objects filtered by the string_short column
 * @method     ChildCanary[]|Collection findByStringMedium(string|array<string> $string_medium) Return ChildCanary objects filtered by the string_medium column
 * @psalm-method Collection&\Traversable<ChildCanary> findByStringMedium(string|array<string> $string_medium) Return ChildCanary objects filtered by the string_medium column
 * @method     ChildCanary[]|Collection findByStringLong(string|array<string> $string_long) Return ChildCanary objects filtered by the string_long column
 * @psalm-method Collection&\Traversable<ChildCanary> findByStringLong(string|array<string> $string_long) Return ChildCanary objects filtered by the string_long column
 * @method     ChildCanary[]|Collection findByTextValue(string|array<string> $text_value) Return ChildCanary objects filtered by the text_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByTextValue(string|array<string> $text_value) Return ChildCanary objects filtered by the text_value column
 * @method     ChildCanary[]|Collection findByDateValue(string|array<string> $date_value) Return ChildCanary objects filtered by the date_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByDateValue(string|array<string> $date_value) Return ChildCanary objects filtered by the date_value column
 * @method     ChildCanary[]|Collection findByDatetimeValue(string|array<string> $datetime_value) Return ChildCanary objects filtered by the datetime_value column
 * @psalm-method Collection&\Traversable<ChildCanary> findByDatetimeValue(string|array<string> $datetime_value) Return ChildCanary objects filtered by the datetime_value column
 * @method     ChildCanary[]|Collection findByNullableString(string|array<string> $nullable_string) Return ChildCanary objects filtered by the nullable_string column
 * @psalm-method Collection&\Traversable<ChildCanary> findByNullableString(string|array<string> $nullable_string) Return ChildCanary objects filtered by the nullable_string column
 * @method     ChildCanary[]|Collection findByNullableInt(int|array<int> $nullable_int) Return ChildCanary objects filtered by the nullable_int column
 * @psalm-method Collection&\Traversable<ChildCanary> findByNullableInt(int|array<int> $nullable_int) Return ChildCanary objects filtered by the nullable_int column
 *
 * @method     ChildCanary[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildCanary> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class CanaryQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ThePHPBench\Propel2\Base\CanaryQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThePHPBench\\Propel2\\Canary', ?string $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildCanaryQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildCanaryQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildCanaryQuery) {
            return $criteria;
        }
        $query = new ChildCanaryQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildCanary|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(CanaryTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = CanaryTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildCanary A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT canary_id, bool_flag, small_int, int_value, big_int, float_value, double_value, decimal_value, fixed_char_8, fixed_char_16, string_short, string_medium, string_long, text_value, date_value, datetime_value, nullable_string, nullable_int FROM canaries WHERE canary_id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildCanary $obj */
            $obj = new ChildCanary();
            $obj->hydrate($row);
            CanaryTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con A connection object
     *
     * @return ChildCanary|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param array $keys Primary keys to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return Collection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ?ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param mixed $key Primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $key, Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param array|int $keys The list of primary key to use for the query
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the canary_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCanaryId(1234); // WHERE canary_id = 1234
     * $query->filterByCanaryId(array(12, 34)); // WHERE canary_id IN (12, 34)
     * $query->filterByCanaryId(array('min' => 12)); // WHERE canary_id > 12
     * </code>
     *
     * @param mixed $canaryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCanaryId($canaryId = null, ?string $comparison = null)
    {
        if (is_array($canaryId)) {
            $useMinMax = false;
            if (isset($canaryId['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $canaryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($canaryId['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $canaryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $canaryId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the bool_flag column
     *
     * Example usage:
     * <code>
     * $query->filterByBoolFlag(true); // WHERE bool_flag = true
     * $query->filterByBoolFlag('yes'); // WHERE bool_flag = true
     * </code>
     *
     * @param bool|string $boolFlag The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBoolFlag($boolFlag = null, ?string $comparison = null)
    {
        if (is_string($boolFlag)) {
            $boolFlag = in_array(strtolower($boolFlag), array('false', 'off', '-', 'no', 'n', '0', ''), true) ? false : true;
        }

        $this->addUsingAlias(CanaryTableMap::COL_BOOL_FLAG, $boolFlag, $comparison);

        return $this;
    }

    /**
     * Filter the query on the small_int column
     *
     * Example usage:
     * <code>
     * $query->filterBySmallInt(1234); // WHERE small_int = 1234
     * $query->filterBySmallInt(array(12, 34)); // WHERE small_int IN (12, 34)
     * $query->filterBySmallInt(array('min' => 12)); // WHERE small_int > 12
     * </code>
     *
     * @param mixed $smallInt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterBySmallInt($smallInt = null, ?string $comparison = null)
    {
        if (is_array($smallInt)) {
            $useMinMax = false;
            if (isset($smallInt['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_SMALL_INT, $smallInt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($smallInt['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_SMALL_INT, $smallInt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_SMALL_INT, $smallInt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the int_value column
     *
     * Example usage:
     * <code>
     * $query->filterByIntValue(1234); // WHERE int_value = 1234
     * $query->filterByIntValue(array(12, 34)); // WHERE int_value IN (12, 34)
     * $query->filterByIntValue(array('min' => 12)); // WHERE int_value > 12
     * </code>
     *
     * @param mixed $intValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIntValue($intValue = null, ?string $comparison = null)
    {
        if (is_array($intValue)) {
            $useMinMax = false;
            if (isset($intValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_INT_VALUE, $intValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($intValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_INT_VALUE, $intValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_INT_VALUE, $intValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the big_int column
     *
     * Example usage:
     * <code>
     * $query->filterByBigInt(1234); // WHERE big_int = 1234
     * $query->filterByBigInt(array(12, 34)); // WHERE big_int IN (12, 34)
     * $query->filterByBigInt(array('min' => 12)); // WHERE big_int > 12
     * </code>
     *
     * @param mixed $bigInt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByBigInt($bigInt = null, ?string $comparison = null)
    {
        if (is_array($bigInt)) {
            $useMinMax = false;
            if (isset($bigInt['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_BIG_INT, $bigInt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bigInt['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_BIG_INT, $bigInt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_BIG_INT, $bigInt, $comparison);

        return $this;
    }

    /**
     * Filter the query on the float_value column
     *
     * Example usage:
     * <code>
     * $query->filterByFloatValue(1234); // WHERE float_value = 1234
     * $query->filterByFloatValue(array(12, 34)); // WHERE float_value IN (12, 34)
     * $query->filterByFloatValue(array('min' => 12)); // WHERE float_value > 12
     * </code>
     *
     * @param mixed $floatValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFloatValue($floatValue = null, ?string $comparison = null)
    {
        if (is_array($floatValue)) {
            $useMinMax = false;
            if (isset($floatValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_FLOAT_VALUE, $floatValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($floatValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_FLOAT_VALUE, $floatValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_FLOAT_VALUE, $floatValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the double_value column
     *
     * Example usage:
     * <code>
     * $query->filterByDoubleValue(1234); // WHERE double_value = 1234
     * $query->filterByDoubleValue(array(12, 34)); // WHERE double_value IN (12, 34)
     * $query->filterByDoubleValue(array('min' => 12)); // WHERE double_value > 12
     * </code>
     *
     * @param mixed $doubleValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDoubleValue($doubleValue = null, ?string $comparison = null)
    {
        if (is_array($doubleValue)) {
            $useMinMax = false;
            if (isset($doubleValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DOUBLE_VALUE, $doubleValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($doubleValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DOUBLE_VALUE, $doubleValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_DOUBLE_VALUE, $doubleValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the decimal_value column
     *
     * Example usage:
     * <code>
     * $query->filterByDecimalValue(1234); // WHERE decimal_value = 1234
     * $query->filterByDecimalValue(array(12, 34)); // WHERE decimal_value IN (12, 34)
     * $query->filterByDecimalValue(array('min' => 12)); // WHERE decimal_value > 12
     * </code>
     *
     * @param mixed $decimalValue The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDecimalValue($decimalValue = null, ?string $comparison = null)
    {
        if (is_array($decimalValue)) {
            $useMinMax = false;
            if (isset($decimalValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DECIMAL_VALUE, $decimalValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($decimalValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DECIMAL_VALUE, $decimalValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_DECIMAL_VALUE, $decimalValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the fixed_char_8 column
     *
     * Example usage:
     * <code>
     * $query->filterByFixedChar8('fooValue');   // WHERE fixed_char_8 = 'fooValue'
     * $query->filterByFixedChar8('%fooValue%', Criteria::LIKE); // WHERE fixed_char_8 LIKE '%fooValue%'
     * $query->filterByFixedChar8(['foo', 'bar']); // WHERE fixed_char_8 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fixedChar8 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFixedChar8($fixedChar8 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fixedChar8)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_FIXED_CHAR_8, $fixedChar8, $comparison);

        return $this;
    }

    /**
     * Filter the query on the fixed_char_16 column
     *
     * Example usage:
     * <code>
     * $query->filterByFixedChar16('fooValue');   // WHERE fixed_char_16 = 'fooValue'
     * $query->filterByFixedChar16('%fooValue%', Criteria::LIKE); // WHERE fixed_char_16 LIKE '%fooValue%'
     * $query->filterByFixedChar16(['foo', 'bar']); // WHERE fixed_char_16 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $fixedChar16 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFixedChar16($fixedChar16 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fixedChar16)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_FIXED_CHAR_16, $fixedChar16, $comparison);

        return $this;
    }

    /**
     * Filter the query on the string_short column
     *
     * Example usage:
     * <code>
     * $query->filterByStringShort('fooValue');   // WHERE string_short = 'fooValue'
     * $query->filterByStringShort('%fooValue%', Criteria::LIKE); // WHERE string_short LIKE '%fooValue%'
     * $query->filterByStringShort(['foo', 'bar']); // WHERE string_short IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $stringShort The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStringShort($stringShort = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stringShort)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_STRING_SHORT, $stringShort, $comparison);

        return $this;
    }

    /**
     * Filter the query on the string_medium column
     *
     * Example usage:
     * <code>
     * $query->filterByStringMedium('fooValue');   // WHERE string_medium = 'fooValue'
     * $query->filterByStringMedium('%fooValue%', Criteria::LIKE); // WHERE string_medium LIKE '%fooValue%'
     * $query->filterByStringMedium(['foo', 'bar']); // WHERE string_medium IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $stringMedium The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStringMedium($stringMedium = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stringMedium)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_STRING_MEDIUM, $stringMedium, $comparison);

        return $this;
    }

    /**
     * Filter the query on the string_long column
     *
     * Example usage:
     * <code>
     * $query->filterByStringLong('fooValue');   // WHERE string_long = 'fooValue'
     * $query->filterByStringLong('%fooValue%', Criteria::LIKE); // WHERE string_long LIKE '%fooValue%'
     * $query->filterByStringLong(['foo', 'bar']); // WHERE string_long IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $stringLong The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStringLong($stringLong = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stringLong)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_STRING_LONG, $stringLong, $comparison);

        return $this;
    }

    /**
     * Filter the query on the text_value column
     *
     * Example usage:
     * <code>
     * $query->filterByTextValue('fooValue');   // WHERE text_value = 'fooValue'
     * $query->filterByTextValue('%fooValue%', Criteria::LIKE); // WHERE text_value LIKE '%fooValue%'
     * $query->filterByTextValue(['foo', 'bar']); // WHERE text_value IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $textValue The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTextValue($textValue = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($textValue)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_TEXT_VALUE, $textValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the date_value column
     *
     * Example usage:
     * <code>
     * $query->filterByDateValue('2011-03-14'); // WHERE date_value = '2011-03-14'
     * $query->filterByDateValue('now'); // WHERE date_value = '2011-03-14'
     * $query->filterByDateValue(array('max' => 'yesterday')); // WHERE date_value > '2011-03-13'
     * </code>
     *
     * @param mixed $dateValue The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDateValue($dateValue = null, ?string $comparison = null)
    {
        if (is_array($dateValue)) {
            $useMinMax = false;
            if (isset($dateValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DATE_VALUE, $dateValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($dateValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DATE_VALUE, $dateValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_DATE_VALUE, $dateValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the datetime_value column
     *
     * Example usage:
     * <code>
     * $query->filterByDatetimeValue('2011-03-14'); // WHERE datetime_value = '2011-03-14'
     * $query->filterByDatetimeValue('now'); // WHERE datetime_value = '2011-03-14'
     * $query->filterByDatetimeValue(array('max' => 'yesterday')); // WHERE datetime_value > '2011-03-13'
     * </code>
     *
     * @param mixed $datetimeValue The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByDatetimeValue($datetimeValue = null, ?string $comparison = null)
    {
        if (is_array($datetimeValue)) {
            $useMinMax = false;
            if (isset($datetimeValue['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DATETIME_VALUE, $datetimeValue['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($datetimeValue['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_DATETIME_VALUE, $datetimeValue['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_DATETIME_VALUE, $datetimeValue, $comparison);

        return $this;
    }

    /**
     * Filter the query on the nullable_string column
     *
     * Example usage:
     * <code>
     * $query->filterByNullableString('fooValue');   // WHERE nullable_string = 'fooValue'
     * $query->filterByNullableString('%fooValue%', Criteria::LIKE); // WHERE nullable_string LIKE '%fooValue%'
     * $query->filterByNullableString(['foo', 'bar']); // WHERE nullable_string IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $nullableString The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNullableString($nullableString = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nullableString)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_NULLABLE_STRING, $nullableString, $comparison);

        return $this;
    }

    /**
     * Filter the query on the nullable_int column
     *
     * Example usage:
     * <code>
     * $query->filterByNullableInt(1234); // WHERE nullable_int = 1234
     * $query->filterByNullableInt(array(12, 34)); // WHERE nullable_int IN (12, 34)
     * $query->filterByNullableInt(array('min' => 12)); // WHERE nullable_int > 12
     * </code>
     *
     * @param mixed $nullableInt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNullableInt($nullableInt = null, ?string $comparison = null)
    {
        if (is_array($nullableInt)) {
            $useMinMax = false;
            if (isset($nullableInt['min'])) {
                $this->addUsingAlias(CanaryTableMap::COL_NULLABLE_INT, $nullableInt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nullableInt['max'])) {
                $this->addUsingAlias(CanaryTableMap::COL_NULLABLE_INT, $nullableInt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(CanaryTableMap::COL_NULLABLE_INT, $nullableInt, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildCanary $canary Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($canary = null)
    {
        if ($canary) {
            $this->addUsingAlias(CanaryTableMap::COL_CANARY_ID, $canary->getCanaryId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the canaries table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            CanaryTableMap::clearInstancePool();
            CanaryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws \Propel\Runtime\Exception\PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CanaryTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(CanaryTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            CanaryTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            CanaryTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
