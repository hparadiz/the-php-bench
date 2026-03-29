<?php

namespace ThePHPBench\Doctrine;

use ThePHPBench\Seed\FieldDefinition;

class Tests extends \ThePHPBench\Test
	{
	protected ?\Doctrine\ORM\EntityManager $entityManager;

	private string $entityClass = \ThePHPBench\Doctrine\Entity\StringVariable::class;

	private string $primaryKey = 'string_variable_id';

	public function closeConnection() : void
		{
		$this->entityManager = null;
		}

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		$entity = $this->read($id);

		if ($entity === null)
			{
			return false;
			}

		$this->entityManager->remove($entity);

		return true;
		}

	public function flush() : void
		{
		$this->entityManager->flush();
		$this->entityManager->clear();
		}

	/**
	 * Initialize the orm
	 *
	 * @param array<string> $lines sql to import into schema
	 */
	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$queryCache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();
		$metadataCache = new \Symfony\Component\Cache\Adapter\ArrayAdapter();

		$doctrineConfig = new \Doctrine\ORM\Configuration();
		$doctrineConfig->setMetadataCache($metadataCache);
		$entityPath = __DIR__ . '/Entity';
		$driverImpl = new \Doctrine\ORM\Mapping\Driver\AttributeDriver([$entityPath], true);
		$doctrineConfig->setMetadataDriverImpl($driverImpl);
		$doctrineConfig->setQueryCache($queryCache);
		$doctrineConfig->setProxyDir(__DIR__ . '/Proxy');
		$doctrineConfig->setProxyNamespace('ThePHPBench\Doctrine\Proxy');
		$doctrineConfig->setAutoGenerateProxyClasses(true);

		// configuring the database connection
		$settings = [
			'driver' => 'pdo_' . $config->getDriver(),
			'host' => $config->getHost(),
			'user' => $config->getUser(),
			'password' => $config->getPassword(),
			'charset' => 'utf8',
			'port' => $config->getPort(),
		];
		$database = $config->getDatabase();

		if (':memory:' == $database)
			{
			$settings['memory'] = true;
			}
		else
			{
			$settings['dbname'] = $database;
			}

		$connection = \Doctrine\DBAL\DriverManager::getConnection($settings, $doctrineConfig);

		// obtaining the entity manager
		$this->entityManager = new \Doctrine\ORM\EntityManager($connection, $doctrineConfig);
		$this->entityClass = '\\ThePHPBench\\Doctrine\\Entity\\' . $this->scenarioClassName($this->getScenarioFamily($config));
		$this->primaryKey = $this->primaryKeyField();
		$callback = [$connection, 'executeQuery'];

		$this->loadSchema($lines, $callback, $runTimer);

		return $this;
		}

	/**
	 * class must insert one record with id=$id
	 *
	 * @return int $id inserted
	 */
	public function insert(int $id) : int
		{
		$employee = new $this->entityClass();
		$employee->{$this->primaryKey} = $id;
		$this->applyPayload($employee, $this->payloadForRow($id));

		$this->entityManager->persist($employee);

		return $id;
		}

	/**
	 * class must read and return one record with id=$id or null on no matching record
	 */
	public function read(int $id) : ?object
		{
		return $this->entityManager->find($this->entityClass, $id);
		}

	/**
	 * class must update one record with id=$id to have $to in the data
	 */
	public function update(int $id, int $to) : bool
		{
		$employee = $this->read($id);
		$this->applyPayload($employee, $this->payloadForRow($to));

		$this->entityManager->persist($employee);

		return true;
		}

	protected function transformPayloadValue(FieldDefinition $field, mixed $value) : mixed
		{
		if (null === $value)
			{
			return null;
			}

		return match ($field->type) {
			'date', 'datetime' => new \DateTimeImmutable((string)$value),
			default => $value,
		};
		}
	}
