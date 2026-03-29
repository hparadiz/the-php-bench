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
use ThePHPBench\Propel2\StringFixed as ChildStringFixed;
use ThePHPBench\Propel2\StringFixedQuery as ChildStringFixedQuery;
use ThePHPBench\Propel2\Map\StringFixedTableMap;

/**
 * Base class that represents a query for the `string_fixed_records` table.
 *
 * @method     ChildStringFixedQuery orderByStringFixedId($order = Criteria::ASC) Order by the string_fixed_id column
 * @method     ChildStringFixedQuery orderByC0801($order = Criteria::ASC) Order by the c08_01 column
 * @method     ChildStringFixedQuery orderByC0802($order = Criteria::ASC) Order by the c08_02 column
 * @method     ChildStringFixedQuery orderByC1601($order = Criteria::ASC) Order by the c16_01 column
 * @method     ChildStringFixedQuery orderByC1602($order = Criteria::ASC) Order by the c16_02 column
 * @method     ChildStringFixedQuery orderByC3201($order = Criteria::ASC) Order by the c32_01 column
 * @method     ChildStringFixedQuery orderByC3202($order = Criteria::ASC) Order by the c32_02 column
 * @method     ChildStringFixedQuery orderByC6401($order = Criteria::ASC) Order by the c64_01 column
 * @method     ChildStringFixedQuery orderByC6402($order = Criteria::ASC) Order by the c64_02 column
 *
 * @method     ChildStringFixedQuery groupByStringFixedId() Group by the string_fixed_id column
 * @method     ChildStringFixedQuery groupByC0801() Group by the c08_01 column
 * @method     ChildStringFixedQuery groupByC0802() Group by the c08_02 column
 * @method     ChildStringFixedQuery groupByC1601() Group by the c16_01 column
 * @method     ChildStringFixedQuery groupByC1602() Group by the c16_02 column
 * @method     ChildStringFixedQuery groupByC3201() Group by the c32_01 column
 * @method     ChildStringFixedQuery groupByC3202() Group by the c32_02 column
 * @method     ChildStringFixedQuery groupByC6401() Group by the c64_01 column
 * @method     ChildStringFixedQuery groupByC6402() Group by the c64_02 column
 *
 * @method     ChildStringFixedQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStringFixedQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStringFixedQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStringFixedQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStringFixedQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStringFixedQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStringFixed|null findOne(?ConnectionInterface $con = null) Return the first ChildStringFixed matching the query
 * @method     ChildStringFixed findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildStringFixed matching the query, or a new ChildStringFixed object populated from the query conditions when no match is found
 *
 * @method     ChildStringFixed|null findOneByStringFixedId(int $string_fixed_id) Return the first ChildStringFixed filtered by the string_fixed_id column
 * @method     ChildStringFixed|null findOneByC0801(string $c08_01) Return the first ChildStringFixed filtered by the c08_01 column
 * @method     ChildStringFixed|null findOneByC0802(string $c08_02) Return the first ChildStringFixed filtered by the c08_02 column
 * @method     ChildStringFixed|null findOneByC1601(string $c16_01) Return the first ChildStringFixed filtered by the c16_01 column
 * @method     ChildStringFixed|null findOneByC1602(string $c16_02) Return the first ChildStringFixed filtered by the c16_02 column
 * @method     ChildStringFixed|null findOneByC3201(string $c32_01) Return the first ChildStringFixed filtered by the c32_01 column
 * @method     ChildStringFixed|null findOneByC3202(string $c32_02) Return the first ChildStringFixed filtered by the c32_02 column
 * @method     ChildStringFixed|null findOneByC6401(string $c64_01) Return the first ChildStringFixed filtered by the c64_01 column
 * @method     ChildStringFixed|null findOneByC6402(string $c64_02) Return the first ChildStringFixed filtered by the c64_02 column
 *
 * @method     ChildStringFixed requirePk($key, ?ConnectionInterface $con = null) Return the ChildStringFixed by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOne(?ConnectionInterface $con = null) Return the first ChildStringFixed matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStringFixed requireOneByStringFixedId(int $string_fixed_id) Return the first ChildStringFixed filtered by the string_fixed_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC0801(string $c08_01) Return the first ChildStringFixed filtered by the c08_01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC0802(string $c08_02) Return the first ChildStringFixed filtered by the c08_02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC1601(string $c16_01) Return the first ChildStringFixed filtered by the c16_01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC1602(string $c16_02) Return the first ChildStringFixed filtered by the c16_02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC3201(string $c32_01) Return the first ChildStringFixed filtered by the c32_01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC3202(string $c32_02) Return the first ChildStringFixed filtered by the c32_02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC6401(string $c64_01) Return the first ChildStringFixed filtered by the c64_01 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringFixed requireOneByC6402(string $c64_02) Return the first ChildStringFixed filtered by the c64_02 column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStringFixed[]|Collection find(?ConnectionInterface $con = null) Return ChildStringFixed objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildStringFixed> find(?ConnectionInterface $con = null) Return ChildStringFixed objects based on current ModelCriteria
 *
 * @method     ChildStringFixed[]|Collection findByStringFixedId(int|array<int> $string_fixed_id) Return ChildStringFixed objects filtered by the string_fixed_id column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByStringFixedId(int|array<int> $string_fixed_id) Return ChildStringFixed objects filtered by the string_fixed_id column
 * @method     ChildStringFixed[]|Collection findByC0801(string|array<string> $c08_01) Return ChildStringFixed objects filtered by the c08_01 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC0801(string|array<string> $c08_01) Return ChildStringFixed objects filtered by the c08_01 column
 * @method     ChildStringFixed[]|Collection findByC0802(string|array<string> $c08_02) Return ChildStringFixed objects filtered by the c08_02 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC0802(string|array<string> $c08_02) Return ChildStringFixed objects filtered by the c08_02 column
 * @method     ChildStringFixed[]|Collection findByC1601(string|array<string> $c16_01) Return ChildStringFixed objects filtered by the c16_01 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC1601(string|array<string> $c16_01) Return ChildStringFixed objects filtered by the c16_01 column
 * @method     ChildStringFixed[]|Collection findByC1602(string|array<string> $c16_02) Return ChildStringFixed objects filtered by the c16_02 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC1602(string|array<string> $c16_02) Return ChildStringFixed objects filtered by the c16_02 column
 * @method     ChildStringFixed[]|Collection findByC3201(string|array<string> $c32_01) Return ChildStringFixed objects filtered by the c32_01 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC3201(string|array<string> $c32_01) Return ChildStringFixed objects filtered by the c32_01 column
 * @method     ChildStringFixed[]|Collection findByC3202(string|array<string> $c32_02) Return ChildStringFixed objects filtered by the c32_02 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC3202(string|array<string> $c32_02) Return ChildStringFixed objects filtered by the c32_02 column
 * @method     ChildStringFixed[]|Collection findByC6401(string|array<string> $c64_01) Return ChildStringFixed objects filtered by the c64_01 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC6401(string|array<string> $c64_01) Return ChildStringFixed objects filtered by the c64_01 column
 * @method     ChildStringFixed[]|Collection findByC6402(string|array<string> $c64_02) Return ChildStringFixed objects filtered by the c64_02 column
 * @psalm-method Collection&\Traversable<ChildStringFixed> findByC6402(string|array<string> $c64_02) Return ChildStringFixed objects filtered by the c64_02 column
 *
 * @method     ChildStringFixed[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildStringFixed> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class StringFixedQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ThePHPBench\Propel2\Base\StringFixedQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThePHPBench\\Propel2\\StringFixed', ?string $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStringFixedQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStringFixedQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildStringFixedQuery) {
            return $criteria;
        }
        $query = new ChildStringFixedQuery();
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
     * @return ChildStringFixed|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StringFixedTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StringFixedTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStringFixed A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT string_fixed_id, c08_01, c08_02, c16_01, c16_02, c32_01, c32_02, c64_01, c64_02 FROM string_fixed_records WHERE string_fixed_id = :p0';
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
            /** @var ChildStringFixed $obj */
            $obj = new ChildStringFixed();
            $obj->hydrate($row);
            StringFixedTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStringFixed|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the string_fixed_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStringFixedId(1234); // WHERE string_fixed_id = 1234
     * $query->filterByStringFixedId(array(12, 34)); // WHERE string_fixed_id IN (12, 34)
     * $query->filterByStringFixedId(array('min' => 12)); // WHERE string_fixed_id > 12
     * </code>
     *
     * @param mixed $stringFixedId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStringFixedId($stringFixedId = null, ?string $comparison = null)
    {
        if (is_array($stringFixedId)) {
            $useMinMax = false;
            if (isset($stringFixedId['min'])) {
                $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $stringFixedId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stringFixedId['max'])) {
                $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $stringFixedId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $stringFixedId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c08_01 column
     *
     * Example usage:
     * <code>
     * $query->filterByC0801('fooValue');   // WHERE c08_01 = 'fooValue'
     * $query->filterByC0801('%fooValue%', Criteria::LIKE); // WHERE c08_01 LIKE '%fooValue%'
     * $query->filterByC0801(['foo', 'bar']); // WHERE c08_01 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c0801 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC0801($c0801 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c0801)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C08_01, $c0801, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c08_02 column
     *
     * Example usage:
     * <code>
     * $query->filterByC0802('fooValue');   // WHERE c08_02 = 'fooValue'
     * $query->filterByC0802('%fooValue%', Criteria::LIKE); // WHERE c08_02 LIKE '%fooValue%'
     * $query->filterByC0802(['foo', 'bar']); // WHERE c08_02 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c0802 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC0802($c0802 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c0802)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C08_02, $c0802, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c16_01 column
     *
     * Example usage:
     * <code>
     * $query->filterByC1601('fooValue');   // WHERE c16_01 = 'fooValue'
     * $query->filterByC1601('%fooValue%', Criteria::LIKE); // WHERE c16_01 LIKE '%fooValue%'
     * $query->filterByC1601(['foo', 'bar']); // WHERE c16_01 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c1601 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC1601($c1601 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c1601)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C16_01, $c1601, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c16_02 column
     *
     * Example usage:
     * <code>
     * $query->filterByC1602('fooValue');   // WHERE c16_02 = 'fooValue'
     * $query->filterByC1602('%fooValue%', Criteria::LIKE); // WHERE c16_02 LIKE '%fooValue%'
     * $query->filterByC1602(['foo', 'bar']); // WHERE c16_02 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c1602 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC1602($c1602 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c1602)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C16_02, $c1602, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c32_01 column
     *
     * Example usage:
     * <code>
     * $query->filterByC3201('fooValue');   // WHERE c32_01 = 'fooValue'
     * $query->filterByC3201('%fooValue%', Criteria::LIKE); // WHERE c32_01 LIKE '%fooValue%'
     * $query->filterByC3201(['foo', 'bar']); // WHERE c32_01 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c3201 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC3201($c3201 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c3201)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C32_01, $c3201, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c32_02 column
     *
     * Example usage:
     * <code>
     * $query->filterByC3202('fooValue');   // WHERE c32_02 = 'fooValue'
     * $query->filterByC3202('%fooValue%', Criteria::LIKE); // WHERE c32_02 LIKE '%fooValue%'
     * $query->filterByC3202(['foo', 'bar']); // WHERE c32_02 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c3202 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC3202($c3202 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c3202)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C32_02, $c3202, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c64_01 column
     *
     * Example usage:
     * <code>
     * $query->filterByC6401('fooValue');   // WHERE c64_01 = 'fooValue'
     * $query->filterByC6401('%fooValue%', Criteria::LIKE); // WHERE c64_01 LIKE '%fooValue%'
     * $query->filterByC6401(['foo', 'bar']); // WHERE c64_01 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c6401 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC6401($c6401 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c6401)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C64_01, $c6401, $comparison);

        return $this;
    }

    /**
     * Filter the query on the c64_02 column
     *
     * Example usage:
     * <code>
     * $query->filterByC6402('fooValue');   // WHERE c64_02 = 'fooValue'
     * $query->filterByC6402('%fooValue%', Criteria::LIKE); // WHERE c64_02 LIKE '%fooValue%'
     * $query->filterByC6402(['foo', 'bar']); // WHERE c64_02 IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $c6402 The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByC6402($c6402 = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($c6402)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringFixedTableMap::COL_C64_02, $c6402, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildStringFixed $stringFixed Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($stringFixed = null)
    {
        if ($stringFixed) {
            $this->addUsingAlias(StringFixedTableMap::COL_STRING_FIXED_ID, $stringFixed->getStringFixedId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the string_fixed_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StringFixedTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StringFixedTableMap::clearInstancePool();
            StringFixedTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StringFixedTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StringFixedTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StringFixedTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StringFixedTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
