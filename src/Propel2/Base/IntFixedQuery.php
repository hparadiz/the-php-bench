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
use ThePHPBench\Propel2\IntFixed as ChildIntFixed;
use ThePHPBench\Propel2\IntFixedQuery as ChildIntFixedQuery;
use ThePHPBench\Propel2\Map\IntFixedTableMap;

/**
 * Base class that represents a query for the `int_fixed_records` table.
 *
 * @method     ChildIntFixedQuery orderByIntFixedId($order = Criteria::ASC) Order by the int_fixed_id column
 * @method     ChildIntFixedQuery orderByI01($order = Criteria::ASC) Order by the i01 column
 * @method     ChildIntFixedQuery orderByI02($order = Criteria::ASC) Order by the i02 column
 * @method     ChildIntFixedQuery orderByI03($order = Criteria::ASC) Order by the i03 column
 * @method     ChildIntFixedQuery orderByI04($order = Criteria::ASC) Order by the i04 column
 * @method     ChildIntFixedQuery orderByI05($order = Criteria::ASC) Order by the i05 column
 * @method     ChildIntFixedQuery orderByI06($order = Criteria::ASC) Order by the i06 column
 * @method     ChildIntFixedQuery orderByI07($order = Criteria::ASC) Order by the i07 column
 * @method     ChildIntFixedQuery orderByI08($order = Criteria::ASC) Order by the i08 column
 * @method     ChildIntFixedQuery orderByI09($order = Criteria::ASC) Order by the i09 column
 * @method     ChildIntFixedQuery orderByI10($order = Criteria::ASC) Order by the i10 column
 * @method     ChildIntFixedQuery orderByI11($order = Criteria::ASC) Order by the i11 column
 * @method     ChildIntFixedQuery orderByI12($order = Criteria::ASC) Order by the i12 column
 *
 * @method     ChildIntFixedQuery groupByIntFixedId() Group by the int_fixed_id column
 * @method     ChildIntFixedQuery groupByI01() Group by the i01 column
 * @method     ChildIntFixedQuery groupByI02() Group by the i02 column
 * @method     ChildIntFixedQuery groupByI03() Group by the i03 column
 * @method     ChildIntFixedQuery groupByI04() Group by the i04 column
 * @method     ChildIntFixedQuery groupByI05() Group by the i05 column
 * @method     ChildIntFixedQuery groupByI06() Group by the i06 column
 * @method     ChildIntFixedQuery groupByI07() Group by the i07 column
 * @method     ChildIntFixedQuery groupByI08() Group by the i08 column
 * @method     ChildIntFixedQuery groupByI09() Group by the i09 column
 * @method     ChildIntFixedQuery groupByI10() Group by the i10 column
 * @method     ChildIntFixedQuery groupByI11() Group by the i11 column
 * @method     ChildIntFixedQuery groupByI12() Group by the i12 column
 *
 * @method     ChildIntFixedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildIntFixedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildIntFixedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildIntFixedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildIntFixedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildIntFixedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildIntFixed|null findOne(?ConnectionInterface $con = null) Return the first ChildIntFixed matching the query
 * @method     ChildIntFixed findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildIntFixed matching the query, or a new ChildIntFixed object populated from the query conditions when no match is found
 *
 * @method     ChildIntFixed|null findOneByIntFixedId(int $int_fixed_id) Return the first ChildIntFixed filtered by the int_fixed_id column
 * @method     ChildIntFixed|null findOneByI01(int $i01) Return the first ChildIntFixed filtered by the i01 column
 * @method     ChildIntFixed|null findOneByI02(int $i02) Return the first ChildIntFixed filtered by the i02 column
 * @method     ChildIntFixed|null findOneByI03(int $i03) Return the first ChildIntFixed filtered by the i03 column
 * @method     ChildIntFixed|null findOneByI04(int $i04) Return the first ChildIntFixed filtered by the i04 column
 * @method     ChildIntFixed|null findOneByI05(int $i05) Return the first ChildIntFixed filtered by the i05 column
 * @method     ChildIntFixed|null findOneByI06(int $i06) Return the first ChildIntFixed filtered by the i06 column
 * @method     ChildIntFixed|null findOneByI07(int $i07) Return the first ChildIntFixed filtered by the i07 column
 * @method     ChildIntFixed|null findOneByI08(int $i08) Return the first ChildIntFixed filtered by the i08 column
 * @method     ChildIntFixed|null findOneByI09(int $i09) Return the first ChildIntFixed filtered by the i09 column
 * @method     ChildIntFixed|null findOneByI10(int $i10) Return the first ChildIntFixed filtered by the i10 column
 * @method     ChildIntFixed|null findOneByI11(int $i11) Return the first ChildIntFixed filtered by the i11 column
 * @method     ChildIntFixed|null findOneByI12(int $i12) Return the first ChildIntFixed filtered by the i12 column
 *
 * @method     ChildIntFixed requirePk($key, ?ConnectionInterface $con = null) Return the ChildIntFixed by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOne(?ConnectionInterface $con = null) Return the first ChildIntFixed matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIntFixed requireOneByIntFixedId(int $int_fixed_id) Return the first ChildIntFixed filtered by the int_fixed_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI01(int $i01) Return the first ChildIntFixed filtered by the i01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI02(int $i02) Return the first ChildIntFixed filtered by the i02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI03(int $i03) Return the first ChildIntFixed filtered by the i03 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI04(int $i04) Return the first ChildIntFixed filtered by the i04 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI05(int $i05) Return the first ChildIntFixed filtered by the i05 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI06(int $i06) Return the first ChildIntFixed filtered by the i06 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI07(int $i07) Return the first ChildIntFixed filtered by the i07 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI08(int $i08) Return the first ChildIntFixed filtered by the i08 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI09(int $i09) Return the first ChildIntFixed filtered by the i09 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI10(int $i10) Return the first ChildIntFixed filtered by the i10 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI11(int $i11) Return the first ChildIntFixed filtered by the i11 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildIntFixed requireOneByI12(int $i12) Return the first ChildIntFixed filtered by the i12 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildIntFixed[]|Collection find(?ConnectionInterface $con = null) Return ChildIntFixed objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildIntFixed> find(?ConnectionInterface $con = null) Return ChildIntFixed objects based on current ModelCriteria
 *
 * @method     ChildIntFixed[]|Collection findByIntFixedId(int|array<int> $int_fixed_id) Return ChildIntFixed objects filtered by the int_fixed_id column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByIntFixedId(int|array<int> $int_fixed_id) Return ChildIntFixed objects filtered by the int_fixed_id column
 * @method     ChildIntFixed[]|Collection findByI01(int|array<int> $i01) Return ChildIntFixed objects filtered by the i01 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI01(int|array<int> $i01) Return ChildIntFixed objects filtered by the i01 column
 * @method     ChildIntFixed[]|Collection findByI02(int|array<int> $i02) Return ChildIntFixed objects filtered by the i02 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI02(int|array<int> $i02) Return ChildIntFixed objects filtered by the i02 column
 * @method     ChildIntFixed[]|Collection findByI03(int|array<int> $i03) Return ChildIntFixed objects filtered by the i03 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI03(int|array<int> $i03) Return ChildIntFixed objects filtered by the i03 column
 * @method     ChildIntFixed[]|Collection findByI04(int|array<int> $i04) Return ChildIntFixed objects filtered by the i04 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI04(int|array<int> $i04) Return ChildIntFixed objects filtered by the i04 column
 * @method     ChildIntFixed[]|Collection findByI05(int|array<int> $i05) Return ChildIntFixed objects filtered by the i05 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI05(int|array<int> $i05) Return ChildIntFixed objects filtered by the i05 column
 * @method     ChildIntFixed[]|Collection findByI06(int|array<int> $i06) Return ChildIntFixed objects filtered by the i06 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI06(int|array<int> $i06) Return ChildIntFixed objects filtered by the i06 column
 * @method     ChildIntFixed[]|Collection findByI07(int|array<int> $i07) Return ChildIntFixed objects filtered by the i07 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI07(int|array<int> $i07) Return ChildIntFixed objects filtered by the i07 column
 * @method     ChildIntFixed[]|Collection findByI08(int|array<int> $i08) Return ChildIntFixed objects filtered by the i08 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI08(int|array<int> $i08) Return ChildIntFixed objects filtered by the i08 column
 * @method     ChildIntFixed[]|Collection findByI09(int|array<int> $i09) Return ChildIntFixed objects filtered by the i09 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI09(int|array<int> $i09) Return ChildIntFixed objects filtered by the i09 column
 * @method     ChildIntFixed[]|Collection findByI10(int|array<int> $i10) Return ChildIntFixed objects filtered by the i10 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI10(int|array<int> $i10) Return ChildIntFixed objects filtered by the i10 column
 * @method     ChildIntFixed[]|Collection findByI11(int|array<int> $i11) Return ChildIntFixed objects filtered by the i11 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI11(int|array<int> $i11) Return ChildIntFixed objects filtered by the i11 column
 * @method     ChildIntFixed[]|Collection findByI12(int|array<int> $i12) Return ChildIntFixed objects filtered by the i12 column
 * @psalm-method Collection&\Traversable<ChildIntFixed> findByI12(int|array<int> $i12) Return ChildIntFixed objects filtered by the i12 column
 *
 * @method     ChildIntFixed[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildIntFixed> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class IntFixedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ThePHPBench\Propel2\Base\IntFixedQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThePHPBench\\Propel2\\IntFixed', ?string $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildIntFixedQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildIntFixedQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildIntFixedQuery) {
            return $criteria;
        }
        $query = new ChildIntFixedQuery();
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
     * @return ChildIntFixed|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(IntFixedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = IntFixedTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildIntFixed A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT int_fixed_id, i01, i02, i03, i04, i05, i06, i07, i08, i09, i10, i11, i12 FROM int_fixed_records WHERE int_fixed_id = :p0';
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
            /** @var ChildIntFixed $obj */
            $obj = new ChildIntFixed();
            $obj->hydrate($row);
            IntFixedTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildIntFixed|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the int_fixed_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIntFixedId(1234); // WHERE int_fixed_id = 1234
     * $query->filterByIntFixedId(array(12, 34)); // WHERE int_fixed_id IN (12, 34)
     * $query->filterByIntFixedId(array('min' => 12)); // WHERE int_fixed_id > 12
     * </code>
     *
     * @param mixed $intFixedId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByIntFixedId($intFixedId = null, ?string $comparison = null)
    {
        if (is_array($intFixedId)) {
            $useMinMax = false;
            if (isset($intFixedId['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $intFixedId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($intFixedId['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $intFixedId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $intFixedId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i01 column
     *
     * Example usage:
     * <code>
     * $query->filterByI01(1234); // WHERE i01 = 1234
     * $query->filterByI01(array(12, 34)); // WHERE i01 IN (12, 34)
     * $query->filterByI01(array('min' => 12)); // WHERE i01 > 12
     * </code>
     *
     * @param mixed $i01 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI01($i01 = null, ?string $comparison = null)
    {
        if (is_array($i01)) {
            $useMinMax = false;
            if (isset($i01['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I01, $i01['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i01['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I01, $i01['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I01, $i01, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i02 column
     *
     * Example usage:
     * <code>
     * $query->filterByI02(1234); // WHERE i02 = 1234
     * $query->filterByI02(array(12, 34)); // WHERE i02 IN (12, 34)
     * $query->filterByI02(array('min' => 12)); // WHERE i02 > 12
     * </code>
     *
     * @param mixed $i02 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI02($i02 = null, ?string $comparison = null)
    {
        if (is_array($i02)) {
            $useMinMax = false;
            if (isset($i02['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I02, $i02['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i02['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I02, $i02['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I02, $i02, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i03 column
     *
     * Example usage:
     * <code>
     * $query->filterByI03(1234); // WHERE i03 = 1234
     * $query->filterByI03(array(12, 34)); // WHERE i03 IN (12, 34)
     * $query->filterByI03(array('min' => 12)); // WHERE i03 > 12
     * </code>
     *
     * @param mixed $i03 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI03($i03 = null, ?string $comparison = null)
    {
        if (is_array($i03)) {
            $useMinMax = false;
            if (isset($i03['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I03, $i03['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i03['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I03, $i03['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I03, $i03, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i04 column
     *
     * Example usage:
     * <code>
     * $query->filterByI04(1234); // WHERE i04 = 1234
     * $query->filterByI04(array(12, 34)); // WHERE i04 IN (12, 34)
     * $query->filterByI04(array('min' => 12)); // WHERE i04 > 12
     * </code>
     *
     * @param mixed $i04 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI04($i04 = null, ?string $comparison = null)
    {
        if (is_array($i04)) {
            $useMinMax = false;
            if (isset($i04['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I04, $i04['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i04['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I04, $i04['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I04, $i04, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i05 column
     *
     * Example usage:
     * <code>
     * $query->filterByI05(1234); // WHERE i05 = 1234
     * $query->filterByI05(array(12, 34)); // WHERE i05 IN (12, 34)
     * $query->filterByI05(array('min' => 12)); // WHERE i05 > 12
     * </code>
     *
     * @param mixed $i05 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI05($i05 = null, ?string $comparison = null)
    {
        if (is_array($i05)) {
            $useMinMax = false;
            if (isset($i05['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I05, $i05['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i05['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I05, $i05['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I05, $i05, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i06 column
     *
     * Example usage:
     * <code>
     * $query->filterByI06(1234); // WHERE i06 = 1234
     * $query->filterByI06(array(12, 34)); // WHERE i06 IN (12, 34)
     * $query->filterByI06(array('min' => 12)); // WHERE i06 > 12
     * </code>
     *
     * @param mixed $i06 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI06($i06 = null, ?string $comparison = null)
    {
        if (is_array($i06)) {
            $useMinMax = false;
            if (isset($i06['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I06, $i06['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i06['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I06, $i06['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I06, $i06, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i07 column
     *
     * Example usage:
     * <code>
     * $query->filterByI07(1234); // WHERE i07 = 1234
     * $query->filterByI07(array(12, 34)); // WHERE i07 IN (12, 34)
     * $query->filterByI07(array('min' => 12)); // WHERE i07 > 12
     * </code>
     *
     * @param mixed $i07 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI07($i07 = null, ?string $comparison = null)
    {
        if (is_array($i07)) {
            $useMinMax = false;
            if (isset($i07['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I07, $i07['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i07['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I07, $i07['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I07, $i07, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i08 column
     *
     * Example usage:
     * <code>
     * $query->filterByI08(1234); // WHERE i08 = 1234
     * $query->filterByI08(array(12, 34)); // WHERE i08 IN (12, 34)
     * $query->filterByI08(array('min' => 12)); // WHERE i08 > 12
     * </code>
     *
     * @param mixed $i08 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI08($i08 = null, ?string $comparison = null)
    {
        if (is_array($i08)) {
            $useMinMax = false;
            if (isset($i08['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I08, $i08['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i08['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I08, $i08['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I08, $i08, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i09 column
     *
     * Example usage:
     * <code>
     * $query->filterByI09(1234); // WHERE i09 = 1234
     * $query->filterByI09(array(12, 34)); // WHERE i09 IN (12, 34)
     * $query->filterByI09(array('min' => 12)); // WHERE i09 > 12
     * </code>
     *
     * @param mixed $i09 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI09($i09 = null, ?string $comparison = null)
    {
        if (is_array($i09)) {
            $useMinMax = false;
            if (isset($i09['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I09, $i09['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i09['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I09, $i09['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I09, $i09, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i10 column
     *
     * Example usage:
     * <code>
     * $query->filterByI10(1234); // WHERE i10 = 1234
     * $query->filterByI10(array(12, 34)); // WHERE i10 IN (12, 34)
     * $query->filterByI10(array('min' => 12)); // WHERE i10 > 12
     * </code>
     *
     * @param mixed $i10 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI10($i10 = null, ?string $comparison = null)
    {
        if (is_array($i10)) {
            $useMinMax = false;
            if (isset($i10['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I10, $i10['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i10['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I10, $i10['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I10, $i10, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i11 column
     *
     * Example usage:
     * <code>
     * $query->filterByI11(1234); // WHERE i11 = 1234
     * $query->filterByI11(array(12, 34)); // WHERE i11 IN (12, 34)
     * $query->filterByI11(array('min' => 12)); // WHERE i11 > 12
     * </code>
     *
     * @param mixed $i11 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI11($i11 = null, ?string $comparison = null)
    {
        if (is_array($i11)) {
            $useMinMax = false;
            if (isset($i11['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I11, $i11['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i11['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I11, $i11['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I11, $i11, $comparison);

        return $this;
    }

    /**
     * Filter the query on the i12 column
     *
     * Example usage:
     * <code>
     * $query->filterByI12(1234); // WHERE i12 = 1234
     * $query->filterByI12(array(12, 34)); // WHERE i12 IN (12, 34)
     * $query->filterByI12(array('min' => 12)); // WHERE i12 > 12
     * </code>
     *
     * @param mixed $i12 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByI12($i12 = null, ?string $comparison = null)
    {
        if (is_array($i12)) {
            $useMinMax = false;
            if (isset($i12['min'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I12, $i12['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($i12['max'])) {
                $this->addUsingAlias(IntFixedTableMap::COL_I12, $i12['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(IntFixedTableMap::COL_I12, $i12, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildIntFixed $intFixed Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($intFixed = null)
    {
        if ($intFixed) {
            $this->addUsingAlias(IntFixedTableMap::COL_INT_FIXED_ID, $intFixed->getIntFixedId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the int_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            IntFixedTableMap::clearInstancePool();
            IntFixedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(IntFixedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(IntFixedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            IntFixedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            IntFixedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
