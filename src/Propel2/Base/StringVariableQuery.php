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
use ThePHPBench\Propel2\StringVariable as ChildStringVariable;
use ThePHPBench\Propel2\StringVariableQuery as ChildStringVariableQuery;
use ThePHPBench\Propel2\Map\StringVariableTableMap;

/**
 * Base class that represents a query for the `string_variable_records` table.
 *
 * @method     ChildStringVariableQuery orderByStringVariableId($order = Criteria::ASC) Order by the string_variable_id column
 * @method     ChildStringVariableQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method     ChildStringVariableQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildStringVariableQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method     ChildStringVariableQuery orderByCompany($order = Criteria::ASC) Order by the company column
 * @method     ChildStringVariableQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method     ChildStringVariableQuery orderByRegion($order = Criteria::ASC) Order by the region column
 * @method     ChildStringVariableQuery orderByPostalCode($order = Criteria::ASC) Order by the postal_code column
 * @method     ChildStringVariableQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method     ChildStringVariableQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method     ChildStringVariableQuery orderByNotes($order = Criteria::ASC) Order by the notes column
 *
 * @method     ChildStringVariableQuery groupByStringVariableId() Group by the string_variable_id column
 * @method     ChildStringVariableQuery groupByName() Group by the name column
 * @method     ChildStringVariableQuery groupByTitle() Group by the title column
 * @method     ChildStringVariableQuery groupByEmail() Group by the email column
 * @method     ChildStringVariableQuery groupByCompany() Group by the company column
 * @method     ChildStringVariableQuery groupByCity() Group by the city column
 * @method     ChildStringVariableQuery groupByRegion() Group by the region column
 * @method     ChildStringVariableQuery groupByPostalCode() Group by the postal_code column
 * @method     ChildStringVariableQuery groupByCountry() Group by the country column
 * @method     ChildStringVariableQuery groupByPhone() Group by the phone column
 * @method     ChildStringVariableQuery groupByNotes() Group by the notes column
 *
 * @method     ChildStringVariableQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildStringVariableQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildStringVariableQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildStringVariableQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildStringVariableQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildStringVariableQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildStringVariable|null findOne(?ConnectionInterface $con = null) Return the first ChildStringVariable matching the query
 * @method     ChildStringVariable findOneOrCreate(?ConnectionInterface $con = null) Return the first ChildStringVariable matching the query, or a new ChildStringVariable object populated from the query conditions when no match is found
 *
 * @method     ChildStringVariable|null findOneByStringVariableId(int $string_variable_id) Return the first ChildStringVariable filtered by the string_variable_id column
 * @method     ChildStringVariable|null findOneByName(string $name) Return the first ChildStringVariable filtered by the name column
 * @method     ChildStringVariable|null findOneByTitle(string $title) Return the first ChildStringVariable filtered by the title column
 * @method     ChildStringVariable|null findOneByEmail(string $email) Return the first ChildStringVariable filtered by the email column
 * @method     ChildStringVariable|null findOneByCompany(string $company) Return the first ChildStringVariable filtered by the company column
 * @method     ChildStringVariable|null findOneByCity(string $city) Return the first ChildStringVariable filtered by the city column
 * @method     ChildStringVariable|null findOneByRegion(string $region) Return the first ChildStringVariable filtered by the region column
 * @method     ChildStringVariable|null findOneByPostalCode(string $postal_code) Return the first ChildStringVariable filtered by the postal_code column
 * @method     ChildStringVariable|null findOneByCountry(string $country) Return the first ChildStringVariable filtered by the country column
 * @method     ChildStringVariable|null findOneByPhone(string $phone) Return the first ChildStringVariable filtered by the phone column
 * @method     ChildStringVariable|null findOneByNotes(string $notes) Return the first ChildStringVariable filtered by the notes column
 *
 * @method     ChildStringVariable requirePk($key, ?ConnectionInterface $con = null) Return the ChildStringVariable by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOne(?ConnectionInterface $con = null) Return the first ChildStringVariable matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStringVariable requireOneByStringVariableId(int $string_variable_id) Return the first ChildStringVariable filtered by the string_variable_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByName(string $name) Return the first ChildStringVariable filtered by the name column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByTitle(string $title) Return the first ChildStringVariable filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByEmail(string $email) Return the first ChildStringVariable filtered by the email column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByCompany(string $company) Return the first ChildStringVariable filtered by the company column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByCity(string $city) Return the first ChildStringVariable filtered by the city column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByRegion(string $region) Return the first ChildStringVariable filtered by the region column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByPostalCode(string $postal_code) Return the first ChildStringVariable filtered by the postal_code column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByCountry(string $country) Return the first ChildStringVariable filtered by the country column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByPhone(string $phone) Return the first ChildStringVariable filtered by the phone column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildStringVariable requireOneByNotes(string $notes) Return the first ChildStringVariable filtered by the notes column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildStringVariable[]|Collection find(?ConnectionInterface $con = null) Return ChildStringVariable objects based on current ModelCriteria
 * @psalm-method Collection&\Traversable<ChildStringVariable> find(?ConnectionInterface $con = null) Return ChildStringVariable objects based on current ModelCriteria
 *
 * @method     ChildStringVariable[]|Collection findByStringVariableId(int|array<int> $string_variable_id) Return ChildStringVariable objects filtered by the string_variable_id column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByStringVariableId(int|array<int> $string_variable_id) Return ChildStringVariable objects filtered by the string_variable_id column
 * @method     ChildStringVariable[]|Collection findByName(string|array<string> $name) Return ChildStringVariable objects filtered by the name column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByName(string|array<string> $name) Return ChildStringVariable objects filtered by the name column
 * @method     ChildStringVariable[]|Collection findByTitle(string|array<string> $title) Return ChildStringVariable objects filtered by the title column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByTitle(string|array<string> $title) Return ChildStringVariable objects filtered by the title column
 * @method     ChildStringVariable[]|Collection findByEmail(string|array<string> $email) Return ChildStringVariable objects filtered by the email column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByEmail(string|array<string> $email) Return ChildStringVariable objects filtered by the email column
 * @method     ChildStringVariable[]|Collection findByCompany(string|array<string> $company) Return ChildStringVariable objects filtered by the company column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByCompany(string|array<string> $company) Return ChildStringVariable objects filtered by the company column
 * @method     ChildStringVariable[]|Collection findByCity(string|array<string> $city) Return ChildStringVariable objects filtered by the city column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByCity(string|array<string> $city) Return ChildStringVariable objects filtered by the city column
 * @method     ChildStringVariable[]|Collection findByRegion(string|array<string> $region) Return ChildStringVariable objects filtered by the region column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByRegion(string|array<string> $region) Return ChildStringVariable objects filtered by the region column
 * @method     ChildStringVariable[]|Collection findByPostalCode(string|array<string> $postal_code) Return ChildStringVariable objects filtered by the postal_code column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByPostalCode(string|array<string> $postal_code) Return ChildStringVariable objects filtered by the postal_code column
 * @method     ChildStringVariable[]|Collection findByCountry(string|array<string> $country) Return ChildStringVariable objects filtered by the country column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByCountry(string|array<string> $country) Return ChildStringVariable objects filtered by the country column
 * @method     ChildStringVariable[]|Collection findByPhone(string|array<string> $phone) Return ChildStringVariable objects filtered by the phone column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByPhone(string|array<string> $phone) Return ChildStringVariable objects filtered by the phone column
 * @method     ChildStringVariable[]|Collection findByNotes(string|array<string> $notes) Return ChildStringVariable objects filtered by the notes column
 * @psalm-method Collection&\Traversable<ChildStringVariable> findByNotes(string|array<string> $notes) Return ChildStringVariable objects filtered by the notes column
 *
 * @method     ChildStringVariable[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 * @psalm-method \Propel\Runtime\Util\PropelModelPager&\Traversable<ChildStringVariable> paginate($page = 1, $maxPerPage = 10, ?ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 */
abstract class StringVariableQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \ThePHPBench\Propel2\Base\StringVariableQuery object.
     *
     * @param string $dbName The database name
     * @param string $modelName The phpName of a model, e.g. 'Book'
     * @param string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\ThePHPBench\\Propel2\\StringVariable', ?string $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildStringVariableQuery object.
     *
     * @param string $modelAlias The alias of a model in the query
     * @param Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildStringVariableQuery
     */
    public static function create(?string $modelAlias = null, ?Criteria $criteria = null): Criteria
    {
        if ($criteria instanceof ChildStringVariableQuery) {
            return $criteria;
        }
        $query = new ChildStringVariableQuery();
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
     * @return ChildStringVariable|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ?ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(StringVariableTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = StringVariableTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildStringVariable A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT string_variable_id, name, title, email, company, city, region, postal_code, country, phone, notes FROM string_variable_records WHERE string_variable_id = :p0';
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
            /** @var ChildStringVariable $obj */
            $obj = new ChildStringVariable();
            $obj->hydrate($row);
            StringVariableTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildStringVariable|array|mixed the result, formatted by the current formatter
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

        $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $key, Criteria::EQUAL);

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

        $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $keys, Criteria::IN);

        return $this;
    }

    /**
     * Filter the query on the string_variable_id column
     *
     * Example usage:
     * <code>
     * $query->filterByStringVariableId(1234); // WHERE string_variable_id = 1234
     * $query->filterByStringVariableId(array(12, 34)); // WHERE string_variable_id IN (12, 34)
     * $query->filterByStringVariableId(array('min' => 12)); // WHERE string_variable_id > 12
     * </code>
     *
     * @param mixed $stringVariableId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByStringVariableId($stringVariableId = null, ?string $comparison = null)
    {
        if (is_array($stringVariableId)) {
            $useMinMax = false;
            if (isset($stringVariableId['min'])) {
                $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $stringVariableId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($stringVariableId['max'])) {
                $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $stringVariableId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $stringVariableId, $comparison);

        return $this;
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%', Criteria::LIKE); // WHERE name LIKE '%fooValue%'
     * $query->filterByName(['foo', 'bar']); // WHERE name IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $name The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByName($name = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_NAME, $name, $comparison);

        return $this;
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * $query->filterByTitle(['foo', 'bar']); // WHERE title IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $title The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByTitle($title = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_TITLE, $title, $comparison);

        return $this;
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%', Criteria::LIKE); // WHERE email LIKE '%fooValue%'
     * $query->filterByEmail(['foo', 'bar']); // WHERE email IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $email The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByEmail($email = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_EMAIL, $email, $comparison);

        return $this;
    }

    /**
     * Filter the query on the company column
     *
     * Example usage:
     * <code>
     * $query->filterByCompany('fooValue');   // WHERE company = 'fooValue'
     * $query->filterByCompany('%fooValue%', Criteria::LIKE); // WHERE company LIKE '%fooValue%'
     * $query->filterByCompany(['foo', 'bar']); // WHERE company IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $company The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCompany($company = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($company)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_COMPANY, $company, $comparison);

        return $this;
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%', Criteria::LIKE); // WHERE city LIKE '%fooValue%'
     * $query->filterByCity(['foo', 'bar']); // WHERE city IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $city The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCity($city = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_CITY, $city, $comparison);

        return $this;
    }

    /**
     * Filter the query on the region column
     *
     * Example usage:
     * <code>
     * $query->filterByRegion('fooValue');   // WHERE region = 'fooValue'
     * $query->filterByRegion('%fooValue%', Criteria::LIKE); // WHERE region LIKE '%fooValue%'
     * $query->filterByRegion(['foo', 'bar']); // WHERE region IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $region The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByRegion($region = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($region)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_REGION, $region, $comparison);

        return $this;
    }

    /**
     * Filter the query on the postal_code column
     *
     * Example usage:
     * <code>
     * $query->filterByPostalCode('fooValue');   // WHERE postal_code = 'fooValue'
     * $query->filterByPostalCode('%fooValue%', Criteria::LIKE); // WHERE postal_code LIKE '%fooValue%'
     * $query->filterByPostalCode(['foo', 'bar']); // WHERE postal_code IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $postalCode The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPostalCode($postalCode = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postalCode)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_POSTAL_CODE, $postalCode, $comparison);

        return $this;
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%', Criteria::LIKE); // WHERE country LIKE '%fooValue%'
     * $query->filterByCountry(['foo', 'bar']); // WHERE country IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $country The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByCountry($country = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_COUNTRY, $country, $comparison);

        return $this;
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%', Criteria::LIKE); // WHERE phone LIKE '%fooValue%'
     * $query->filterByPhone(['foo', 'bar']); // WHERE phone IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $phone The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByPhone($phone = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_PHONE, $phone, $comparison);

        return $this;
    }

    /**
     * Filter the query on the notes column
     *
     * Example usage:
     * <code>
     * $query->filterByNotes('fooValue');   // WHERE notes = 'fooValue'
     * $query->filterByNotes('%fooValue%', Criteria::LIKE); // WHERE notes LIKE '%fooValue%'
     * $query->filterByNotes(['foo', 'bar']); // WHERE notes IN ('foo', 'bar')
     * </code>
     *
     * @param string|string[] $notes The value to use as filter.
     * @param string|null $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this The current query, for fluid interface
     */
    public function filterByNotes($notes = null, ?string $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($notes)) {
                $comparison = Criteria::IN;
            }
        }

        $this->addUsingAlias(StringVariableTableMap::COL_NOTES, $notes, $comparison);

        return $this;
    }

    /**
     * Exclude object from result
     *
     * @param ChildStringVariable $stringVariable Object to remove from the list of results
     *
     * @return $this The current query, for fluid interface
     */
    public function prune($stringVariable = null)
    {
        if ($stringVariable) {
            $this->addUsingAlias(StringVariableTableMap::COL_STRING_VARIABLE_ID, $stringVariable->getStringVariableId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the string_variable_records table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(?ConnectionInterface $con = null): int
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StringVariableTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            StringVariableTableMap::clearInstancePool();
            StringVariableTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(StringVariableTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(StringVariableTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            StringVariableTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            StringVariableTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

}
