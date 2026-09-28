<?php

declare(strict_types=1);

namespace ThePHPBench;

final class FrameworkCatalog
	{
	/**
	 * @var array<string, array{
	 *   display_name: string,
	 *   package_name?: string,
	 *   release_version?: string,
	 *   local_repo?: string
	 * }>
	 */
	private const DEFINITIONS = [
		'ActiveRecord' => [
			'display_name' => 'ActiveRecord',
			'package_name' => 'php-patterns/activerecord',
		],
		'Cake' => [
			'display_name' => 'Cake',
			'package_name' => 'cakephp/cakephp',
		],
		'CakeCached' => [
			'display_name' => 'CakeCached',
			'package_name' => 'cakephp/cakephp',
		],
		'Cycle' => [
			'display_name' => 'Cycle',
			'package_name' => 'cycle/database',
		],
		'Doctrine' => [
			'display_name' => 'Doctrine',
			'package_name' => 'doctrine/orm',
		],
		'DivergenceV2' => [
			'display_name' => 'Divergence',
			'package_name' => 'divergence/divergence',
			'release_version' => 'v2.1.4',
		],
		'DivergenceV3' => [
			'display_name' => 'Divergence',
			'package_name' => 'divergence/divergence',
			'release_version' => 'v3.3.0',
		],
		'DivergenceV3Local' => [
			'display_name' => 'Divergence',
			'package_name' => 'divergence/divergence',
			'local_repo' => 'local/DivergenceV3',
		],
		'Eloquent' => [
			'display_name' => 'Eloquent',
			'package_name' => 'illuminate/database',
		],
		'PHPFUI' => [
			'display_name' => 'PHPFUI',
			'package_name' => 'phpfui/orm',
		],
		'PHPFUIBatch' => [
			'display_name' => 'PHPFUIBatch',
			'package_name' => 'phpfui/orm',
		],
		'Propel2' => [
			'display_name' => 'Propel2',
			'package_name' => 'propel/propel',
		],
		'RedBean' => [
			'display_name' => 'RedBean',
			'package_name' => 'gabordemooij/redbean',
		],
		'Atlas' => [
			'display_name' => 'Atlas',
			'package_name' => 'atlas/pdo',
		],
		'Yii' => [
			'display_name' => 'Yii',
			'package_name' => 'yiisoft/yii2',
		],
	];

	/**
	 * @return list<string>
	 */
	public static function knownFrameworks() : array
		{
		return \array_keys(self::DEFINITIONS);
		}

	public static function displayName(string $framework) : string
		{
		return self::DEFINITIONS[$framework]['display_name'] ?? $framework;
		}

	public static function packageName(string $framework) : string
		{
		return self::DEFINITIONS[$framework]['package_name'] ?? '';
		}

	public static function releaseVersion(string $framework) : string
		{
		return self::DEFINITIONS[$framework]['release_version'] ?? '';
		}

	public static function localRepoPath(string $framework) : ?string
		{
		$relative = self::DEFINITIONS[$framework]['local_repo'] ?? null;

		if (! \is_string($relative) || $relative === '')
			{
			return null;
			}

		return \dirname(__DIR__) . '/' . $relative;
		}
	}
