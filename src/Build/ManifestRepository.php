<?php

declare(strict_types=1);

namespace ThePHPBench\Build;

use ThePHPBench\RuntimeRegistry;

final class ManifestRepository
	{
	/**
	 * @return array<string, mixed>
	 */
	public static function controller() : array
		{
		return [
			'type' => 'controller',
			'name' => 'the-php-bench-controller',
			'entrypoint' => 'bin/the-php-bench',
			'mappings' => self::controllerMappings(),
		];
		}

	/**
	 * @return array<string, mixed>
	 */
	public static function runtime(string $framework) : array
		{
		$known = self::knownFrameworks();

		if (! \in_array($framework, $known, true))
			{
			throw new \InvalidArgumentException("Unknown runtime framework: {$framework}");
			}

		$mappings = [
			'src/RuntimeWorkerEntrypoint.php' => 'src/RuntimeWorkerEntrypoint.php',
			'src' => 'src',
			'config' => 'config',
			'storage/dumps' => 'storage/dumps',
			'system-manifest.json' => 'system-manifest.json',
			'runtimes/' . RuntimeRegistry::runtimeDirNameForFramework($framework) => 'runtimes/' . RuntimeRegistry::runtimeDirNameForFramework($framework),
		];

		$manifest = [
			'type' => 'runtime',
			'name' => 'the-php-bench-runtime-' . \strtolower($framework),
			'framework' => $framework,
			'entrypoint' => 'src/RuntimeWorkerEntrypoint.php',
			'mappings' => $mappings,
		];

		return $manifest;
		}

	/**
	 * @param list<string> $frameworks
	 * @return array<string, mixed>
	 */
	public static function bundle(string $name, array $frameworks) : array
		{
		$frameworks = \array_values(\array_unique($frameworks));

		foreach ($frameworks as $framework)
			{
			self::runtime($framework);
			}

		$mappings = self::controllerMappings();

		foreach ($frameworks as $framework)
			{
			$filename = RuntimeRegistry::pharFilenameForFramework($framework);
			$mappings['dist/' . $filename] = 'dist/' . $filename;
			}

		$manifest = [
			'type' => 'bundle',
			'name' => 'the-php-bench-bundle-' . $name,
			'entrypoint' => 'bin/the-php-bench',
			'frameworks' => $frameworks,
			'mappings' => $mappings,
		];

		return $manifest;
		}

	/**
	 * @return list<string>
	 */
	public static function knownFrameworks() : array
		{
		return [
			'ActiveRecord',
			'Atlas',
			'Cake',
			'CakeCached',
			'Cycle',
			'Doctrine',
			'DivergenceV2',
			'DivergenceV3',
			'Eloquent',
			'PHPFUI',
			'PHPFUIBatch',
			'Propel2',
			'RedBean',
			'Yii',
		];
		}

	/**
	 * @return array<string, string>
	 */
	private static function controllerMappings() : array
		{
		return [
			'bin/the-php-bench' => 'bin/the-php-bench',
			'src/RuntimeWorkerEntrypoint.php' => 'src/RuntimeWorkerEntrypoint.php',
			'src' => 'src',
			'config' => 'config',
			'storage/dumps' => 'storage/dumps',
			'composer.json' => 'composer.json',
			'composer.lock' => 'composer.lock',
			'system-manifest.json' => 'system-manifest.json',
			'vendor' => 'vendor',
		];
		}
	}
