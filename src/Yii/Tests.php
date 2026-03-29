<?php

declare(strict_types=1);

namespace ThePHPBench\Yii;

final class Tests extends \ThePHPBench\Test
	{
	private ?\yii\db\Connection $connection = null;

	public function closeConnection() : void
		{
		if ($this->connection !== null)
			{
			$this->connection->close();
			}

		Record::$db = null;
		$this->connection = null;
		}

	public function delete(int $id) : bool
		{
		$record = $this->read($id);

		return $record instanceof Record ? (bool)$record->delete() : false;
		}

	public function init(\ThePHPBench\Configuration $config, array $lines, \ThePHPBench\BaseLine $runTimer) : static
		{
		$this->connection = new \yii\db\Connection([
			'dsn' => $config->getPDOConnectionString(),
			'username' => $config->getUser(),
			'password' => $config->getPassword(),
			'charset' => 'utf8',
		]);
		$this->connection->open();
		Record::$db = $this->connection;
		Record::$table = $this->resolveScenario($config)->tableName;
		Record::$primaryKeyField = $this->primaryKeyField();
		$this->loadSchema($lines, [$this->connection, 'createCommand'], $runTimer);

		return $this;
		}

	public function insert(int $id) : int
		{
		$record = new Record();
		$record->{Record::$primaryKeyField} = $id;
		$this->applyPayload($record, $this->payloadForRow($id));
		$record->save(false);

		return $id;
		}

	public function read(int $id) : ?object
		{
		return Record::findOne($id);
		}

	public function update(int $id, int $to) : bool
		{
		$record = $this->read($id);

		if (! $record instanceof Record)
			{
			return false;
			}

		$this->applyPayload($record, $this->payloadForRow($to));

		return $record->save(false);
		}

	public function loadSchema(array $lines, callable $callback, \ThePHPBench\BaseLine $runTimer) : static
		{
		$runTimer->pause();
		$sql = '';

		foreach ($lines as $line)
			{
			if (\str_starts_with((string)$line, '--') || '' === $line)
				{
				continue;
				}

			$sql .= $line;

			if (\str_ends_with(\trim((string)$line), ';'))
				{
				$this->connection?->createCommand($sql)->execute();
				$sql = '';
				}
			}

		$runTimer->resume();

		return $this;
		}
	}
