<?php

namespace ThePHPBench\PHPFUIBatch;

class Tests extends \ThePHPBench\PHPFUI\Tests
	{
	private const CHUNK_SIZE = 200;

	/** @var array<int> $deletes */
	private array $deletes = [];

	/** @var array<object> $inserts */
	private array $inserts = [];

	/**
	 * class must delete one record with id=$id
	 */
	public function delete(int $id) : bool
		{
		$this->deletes[] = $id;

		return true;
		}

	/**
	 * override to flush buffers between tests if needed
	 */
	public function flush() : void
		{
		if ($this->deletes)
			{
			foreach (\array_chunk($this->deletes, self::CHUNK_SIZE) as $ids)
				{
				$tableClass = $this->getTableClassName();
				$table = new $tableClass();
				$table->setWhere(new \PHPFUI\ORM\Condition($this->getPrimaryKeyField(), $ids, new \PHPFUI\ORM\Operator\In()));
				$table->delete();
				}
			$this->deletes = [];
			}

		if ($this->inserts)
			{
			foreach (\array_chunk($this->inserts, self::CHUNK_SIZE) as $employees)
				{
				$tableClass = $this->getTableClassName();
				$table = new $tableClass();
				$table->insert($employees);
				}
			$this->inserts = [];
			}
		}

	/**
	 * class must insert one record with id=$id
	 *
	 * @return int $id inserted
	 */
	public function insert(int $id) : int
		{
		$recordClass = $this->getRecordClassName();
		$employee = new $recordClass();
		$employee->{$this->getPrimaryKeyField()} = $id;
		$this->applyPayload($employee, $this->payloadForRow($id));

		$this->inserts[] = $employee;

		return $id;
		}
	}
