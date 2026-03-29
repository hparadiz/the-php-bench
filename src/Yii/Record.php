<?php

declare(strict_types=1);

namespace ThePHPBench\Yii;

final class Record extends \yii\db\ActiveRecord
	{
	public static ?\yii\db\Connection $db = null;

	public static string $primaryKeyField = 'string_variable_id';

	public static string $table = 'string_variable';

	public static function getDb() : \yii\db\Connection
		{
		return self::$db ?? throw new \RuntimeException('Yii DB connection not initialized');
		}

	public static function primaryKey() : array
		{
		return [self::$primaryKeyField];
		}

	public static function tableName() : string
		{
		return self::$table;
		}
	}
