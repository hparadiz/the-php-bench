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
use ThePHPBench\Propel2\FloatFixed as ChildFloatFixed;
use ThePHPBench\Propel2\FloatFixedQuery as ChildFloatFixedQuery;
use ThePHPBench\Propel2\Map\FloatFixedTableMap;

/**
 * Base class that represents a query for the `float_fixed_records` table.
 *
 * @method     ChildFloatFixedQuery orderByFloatFixedId($order = Criteria::ASC) Order by the float_fixed_id column
 * @method     ChildFloatFixedQuery orderByF01($order = Criteria::ASC) Order by the f01 column
 * @method     ChildFloatFixedQuery orderByF02($order = Criteria::ASC) Order by the f02 column
 * @method     ChildFloatFixedQuery orderByD01($order = Criteria::ASC) Order by the d01 column
 * @method     ChildFloatFixedQuery orderByD02($order = Criteria::ASC) Order by the d02 column
 * @method     ChildFloatFixedQuery orderByN01($order = Criteria::ASC) Order by the n01 column
 * @method     ChildFloatFixedQuery orderByN02($order = Criteria::ASC) Order by the n02 column
 * @method     ChildFloatFixedQuery orderByN03($order = Criteria::ASC) Order by the n03 column
 * @method     ChildFloatFixedQuery orderByN04($order = Criteria::ASC) Order by the n04 column
 *
 * @method     ChildFloatFixedQuery groupByFloatFixedId() Group by the float_fixed_id column
 * @method     ChildFloatFixedQuery groupByF01() Group by the f01 column
 * @method     ChildFloatFixedQuery groupByF02() Group by the f02 column
 * @method     ChildFloatFixedQuery groupByD01() Group by the d01 column
 * @method     ChildFloatFixedQuery groupByD02() Group by the d02 column
 * @method     ChildFloatFixedQuery groupByN01() Group by the n01 column
 * @method     ChildFloatFixedQuery groupByN02() Group by the n02 column
 * @method     ChildFloatFixedQuery groupByN03() Group by the n03 column
 * @method     ChildFloatFixedQuery groupByN04() Group by the n04 column
 *
 * @method     ChildFloatFixedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildFloatFixedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildFloatFixedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildFloatFixedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildFloatFixedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildFloatFixedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildFloatFixed|null findOne(?ConnectionInterface $con = null) Return the first ChildFloatFixed matching the query
 * @method     ChildFloatFixed findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildFloatFixed matching the query, or a new ChildFloatFixed object populated from the query conditions when no match is found
 *
 * @method     ChildFloatFixed|null findOneByFloatFixedId(int $float_fixed_id) Return the first ChildFloatFixed filtered by the float_fixed_id column
 * @method     ChildFloatFixed|null findOneByF01(double $f01) Return the first ChildFloatFixed filtered by the f01 column
 * @method     ChildFloatFixed|null findOneByF02(double $f02) Return the first ChildFloatFixed filtered by the f02 column
 * @method     ChildFloatFixed|null findOneByD01(double $d01) Return the first ChildFloatFixed filtered by the d01 column
 * @method     ChildFloatFixed|null findOneByD02(double $d02) Return the first ChildFloatFixed filtered by the d02 column
 * @method     ChildFloatFixed|null findOneByN01(string $n01) Return the first ChildFloatFixed filtered by the n01 column
 * @method     ChildFloatFixed|null findOneByN02(string $n02) Return the first ChildFloatFixed filtered by the n02 column
 * @method     ChildFloatFixed|null findOneByN03(string $n03) Return the first ChildFloatFixed filtered by the n03 column
 * @method     ChildFloatFixed|null findOneByN04(string $n04) Return the first ChildFloatFixed filtered by the n04 column
 *
 * @method     ChildFloatFixed requirePk($key, ?ConnectionInterface $con = null) Return the ChildFloatFixed by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOne(?ConnectionInterface $con = null) Return the first ChildFloatFixed matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFloatFixed requireOneByFloatFixedId(int $float_fixed_id) Return the first ChildFloatFixed filtered by the float_fixed_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByF01(double $f01) Return the first ChildFloatFixed filtered by the f01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByF02(double $f02) Return the first ChildFloatFixed filtered by the f02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByD01(double $d01) Return the first ChildFloatFixed filtered by the d01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByD02(double $d02) Return the first ChildFloatFixed filtered by the d02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByN01(string $n01) Return the first ChildFloatFixed filtered by the n01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByN02(string $n02) Return the first ChildFloatFixed filtered by the n02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByN03(string $n03) Return the first ChildFloatFixed filtered by the n03 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildFloatFixed requireOneByN04(string $n04) Return the first ChildFloatFixed filtered by the n04 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildFloatFixed[]|Collection find(?ConnectionInterface $con = null) Return ChildFloatFixed objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildFloatFixed> find(?ConnectionInterface $con = null) Return ChildFloatFixed objects based on current ModelCriteria
 *
 * @method     ChildFloatFixed[]|Collection findByFloatFixedId(int|array<int> $float_fixed_id) Return ChildFloatFixed objects filtered by the float_fixed_id column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByFloatFixedId(int|array<int> $float_fixed_id) Return ChildFloatFixed objects filtered by the float_fixed_id column
 * @method     ChildFloatFixed[]|Collection findByF01(double|array<double> $f01) Return ChildFloatFixed objects filtered by the f01 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByF01(double|array<double> $f01) Return ChildFloatFixed objects filtered by the f01 column
 * @method     ChildFloatFixed[]|Collection findByF02(double|array<double> $f02) Return ChildFloatFixed objects filtered by the f02 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByF02(double|array<double> $f02) Return ChildFloatFixed objects filtered by the f02 column
 * @method     ChildFloatFixed[]|Collection findByD01(double|array<double> $d01) Return ChildFloatFixed objects filtered by the d01 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByD01(double|array<double> $d01) Return ChildFloatFixed objects filtered by the d01 column
 * @method     ChildFloatFixed[]|Collection findByD02(double|array<double> $d02) Return ChildFloatFixed objects filtered by the d02 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByD02(double|array<double> $d02) Return ChildFloatFixed objects filtered by the d02 column
 * @method     ChildFloatFixed[]|Collection findByN01(string|array<string> $n01) Return ChildFloatFixed objects filtered by the n01 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByN01(string|array<string> $n01) Return ChildFloatFixed objects filtered by the n01 column
 * @method     ChildFloatFixed[]|Collection findByN02(string|array<string> $n02) Return ChildFloatFixed objects filtered by the n02 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByN02(string|array<string> $n02) Return ChildFloatFixed objects filtered by the n02 column
 * @method     ChildFloatFixed[]|Collection findByN03(string|array<string> $n03) Return ChildFloatFixed objects filtered by the n03 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByN03(string|array<string> $n03) Return ChildFloatFixed objects filtered by the n03 column
 * @method     ChildFloatFixed[]|Collection findByN04(string|array<string> $n04) Return ChildFloatFixed objects filtered by the n04 column
 * @psalm-method Collection&\Traversable<ChildFloatFixed> findByN04(string|array<string> $n04) Return ChildFloatFixed objects filtered by the n04 column
 *
 * @method     ChildFloatFixed[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildFloatFixed> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class FloatFixedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ThePHPBench\Propel2\Base\FloatFixedQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThePHPBench\\Propel2\\FloatFixed', ?string $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildFloatFixedQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildFloatFixedQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildFloatFixedQuery) {
            return $criteria;
        }
        $query = new ChildFloatFixedQuery();
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
     * @return ChildFloatFixed|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = FloatFixedTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildFloatFixed A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT float_fixed_id, f01, f02, d01, d02, n01, n02, n03, n04 FROM float_fixed_records WHERE float_fixed_id = :p0';
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
            /** @var ChildFloatFixed $obj */
            $obj = new ChildFloatFixed();
            $obj->hydrate($row);
            FloatFixedTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildFloatFixed|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the float_fixed_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFloatFixedId(1234); // WHERE float_fixed_id = 1234
     * $query->filterByFloatFixedId(array(12, 34)); // WHERE float_fixed_id IN (12, 34)
     * $query->filterByFloatFixedId(array('min' => 12)); // WHERE float_fixed_id > 12
     * </code>
     *
     * @param mixed $floatFixedId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByFloatFixedId($floatFixedId = null, ?string $comparison = null)
    {
        if (is_array($floatFixedId)) {
            $useMinMax = false;
            if (isset($floatFixedId['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $floatFixedId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($floatFixedId['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $floatFixedId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $floatFixedId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the f01 column
     *
     * Example usage:
     * <code>
     * $query->filterByF01(1234); // WHERE f01 = 1234
     * $query->filterByF01(array(12, 34)); // WHERE f01 IN (12, 34)
     * $query->filterByF01(array('min' => 12)); // WHERE f01 > 12
     * </code>
     *
     * @param mixed $f01 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByF01($f01 = null, ?string $comparison = null)
    {
        if (is_array($f01)) {
            $useMinMax = false;
            if (isset($f01['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_F01, $f01['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($f01['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_F01, $f01['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_F01, $f01, $comparison);

        return $this;
    }

    /**
     * Filter the query on the f02 column
     *
     * Example usage:
     * <code>
     * $query->filterByF02(1234); // WHERE f02 = 1234
     * $query->filterByF02(array(12, 34)); // WHERE f02 IN (12, 34)
     * $query->filterByF02(array('min' => 12)); // WHERE f02 > 12
     * </code>
     *
     * @param mixed $f02 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByF02($f02 = null, ?string $comparison = null)
    {
        if (is_array($f02)) {
            $useMinMax = false;
            if (isset($f02['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_F02, $f02['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($f02['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_F02, $f02['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_F02, $f02, $comparison);

        return $this;
    }

    /**
     * Filter the query on the d01 column
     *
     * Example usage:
     * <code>
     * $query->filterByD01(1234); // WHERE d01 = 1234
     * $query->filterByD01(array(12, 34)); // WHERE d01 IN (12, 34)
     * $query->filterByD01(array('min' => 12)); // WHERE d01 > 12
     * </code>
     *
     * @param mixed $d01 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByD01($d01 = null, ?string $comparison = null)
    {
        if (is_array($d01)) {
            $useMinMax = false;
            if (isset($d01['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_D01, $d01['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($d01['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_D01, $d01['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_D01, $d01, $comparison);

        return $this;
    }

    /**
     * Filter the query on the d02 column
     *
     * Example usage:
     * <code>
     * $query->filterByD02(1234); // WHERE d02 = 1234
     * $query->filterByD02(array(12, 34)); // WHERE d02 IN (12, 34)
     * $query->filterByD02(array('min' => 12)); // WHERE d02 > 12
     * </code>
     *
     * @param mixed $d02 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByD02($d02 = null, ?string $comparison = null)
    {
        if (is_array($d02)) {
            $useMinMax = false;
            if (isset($d02['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_D02, $d02['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($d02['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_D02, $d02['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_D02, $d02, $comparison);

        return $this;
    }

    /**
     * Filter the query on the n01 column
     *
     * Example usage:
     * <code>
     * $query->filterByN01(1234); // WHERE n01 = 1234
     * $query->filterByN01(array(12, 34)); // WHERE n01 IN (12, 34)
     * $query->filterByN01(array('min' => 12)); // WHERE n01 > 12
     * </code>
     *
     * @param mixed $n01 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByN01($n01 = null, ?string $comparison = null)
    {
        if (is_array($n01)) {
            $useMinMax = false;
            if (isset($n01['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N01, $n01['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($n01['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N01, $n01['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_N01, $n01, $comparison);

        return $this;
    }

    /**
     * Filter the query on the n02 column
     *
     * Example usage:
     * <code>
     * $query->filterByN02(1234); // WHERE n02 = 1234
     * $query->filterByN02(array(12, 34)); // WHERE n02 IN (12, 34)
     * $query->filterByN02(array('min' => 12)); // WHERE n02 > 12
     * </code>
     *
     * @param mixed $n02 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByN02($n02 = null, ?string $comparison = null)
    {
        if (is_array($n02)) {
            $useMinMax = false;
            if (isset($n02['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N02, $n02['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($n02['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N02, $n02['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_N02, $n02, $comparison);

        return $this;
    }

    /**
     * Filter the query on the n03 column
     *
     * Example usage:
     * <code>
     * $query->filterByN03(1234); // WHERE n03 = 1234
     * $query->filterByN03(array(12, 34)); // WHERE n03 IN (12, 34)
     * $query->filterByN03(array('min' => 12)); // WHERE n03 > 12
     * </code>
     *
     * @param mixed $n03 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByN03($n03 = null, ?string $comparison = null)
    {
        if (is_array($n03)) {
            $useMinMax = false;
            if (isset($n03['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N03, $n03['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($n03['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N03, $n03['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_N03, $n03, $comparison);

        return $this;
    }

    /**
     * Filter the query on the n04 column
     *
     * Example usage:
     * <code>
     * $query->filterByN04(1234); // WHERE n04 = 1234
     * $query->filterByN04(array(12, 34)); // WHERE n04 IN (12, 34)
     * $query->filterByN04(array('min' => 12)); // WHERE n04 > 12
     * </code>
     *
     * @param mixed $n04 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByN04($n04 = null, ?string $comparison = null)
    {
        if (is_array($n04)) {
            $useMinMax = false;
            if (isset($n04['min'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N04, $n04['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($n04['max'])) {
                $this->addUsingAlias(FloatFixedTableMap::COL_N04, $n04['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(FloatFixedTableMap::COL_N04, $n04, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildFloatFixed $floatFixed Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($floatFixed = null)
    {
        if ($floatFixed) {
            $this->addUsingAlias(FloatFixedTableMap::COL_FLOAT_FIXED_ID, $floatFixed->getFloatFixedId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the float_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FloatFixedTableMap::clearInstancePool();
            FloatFixedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(FloatFixedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(FloatFixedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            FloatFixedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            FloatFixedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
