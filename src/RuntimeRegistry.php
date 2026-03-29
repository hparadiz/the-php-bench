<?php

declare(strict_types=1);

namespace ThePHPBench;

use ThePHPBench\Build\ManifestRepository;

final class RuntimeRegistry
	{
	/**
	 * @return list<array{
	 *   name: string,
	 *   display_name: string,
	 *   composer_name: string,
	 *   version: string,
	 *   package_count: int,
	 *   homepage: string,
	 *   runtime_dir: string,
	 *   default_phar_path: string,
	 *   selected_phar_path: string,
	 *   phar_path: string,
	 *   runtime_dir_exists: bool,
	 *   phar_built: bool
	 * }>
	 */
	public static function availableRuntimes() : array
		{
		$runtimes = [];

		foreach (ManifestRepository::knownFrameworks() as $framework)
			{
			$runtimeDir = self::runtimeDirForFramework($framework);
			$defaultPharPath = self::defaultPharPathForFramework($framework);
			$selectedPharPath = self::selectedPharPathForFramework($framework) ?? '';
			$pharPath = self::pharPathForFramework($framework);
			$composer = self::runtimeComposerMetadata($runtimeDir);

			$runtimes[] = [
				'name' => $framework,
				'display_name' => DisplayName::forFramework($framework),
				'composer_name' => (string)($composer['name'] ?? ''),
				'version' => self::runtimeVersion($framework, $runtimeDir, $composer),
				'package_count' => self::installedPackageCount($runtimeDir),
				'homepage' => self::runtimeHomepage($framework, $runtimeDir, $composer),
				'runtime_dir' => $runtimeDir,
				'default_phar_path' => $defaultPharPath,
				'selected_phar_path' => $selectedPharPath,
				'phar_path' => $pharPath,
				'runtime_dir_exists' => \is_dir($runtimeDir),
				'phar_built' => \is_file($pharPath),
			];
			}

		return $runtimes;
		}

	public static function pharFilenameForFramework(string $framework) : string
		{
		$runtimeDir = self::runtimeDirForFramework($framework);
		$composer = self::runtimeComposerMetadata($runtimeDir);
		$version = self::runtimeVersion($framework, $runtimeDir, $composer);

		return \sprintf(
			'runtime-%s-%s.phar',
			\strtolower($framework),
			ApplicationVersion::normalize($version)
		);
		}

	public static function pharPathForFramework(string $framework) : string
		{
		return self::selectedPharPathForFramework($framework) ?? self::defaultPharPathForFramework($framework);
		}

	public static function defaultPharPathForFramework(string $framework) : string
		{
		return \dirname(__DIR__) . '/dist/' . self::pharFilenameForFramework($framework);
		}

	public static function runtimeDirForFramework(string $framework) : string
		{
		return \dirname(__DIR__) . '/runtimes/' . self::runtimeDirNameForFramework($framework);
		}

	public static function runtimeDirNameForFramework(string $framework) : string
		{
		$resolved = self::installedRuntimeDirNameForFramework($framework);

		if ($resolved !== null)
			{
			return $resolved;
			}

		$displayName = DisplayName::forFramework($framework);
		$version = FrameworkVersion::forTest($framework);

		return $version !== '' ? $displayName . '-' . $version : $displayName;
		}

	private static function installedRuntimeDirNameForFramework(string $framework) : ?string
		{
		$runtimesRoot = \dirname(__DIR__) . '/runtimes';
		$expectedPackage = 'divergence/the-php-bench-runtime-' . \strtolower($framework);

		foreach (\glob($runtimesRoot . '/*/composer.json') ?: [] as $composerPath)
			{
			$decoded = \json_decode((string)\file_get_contents($composerPath), true);

			if (! \is_array($decoded) || ($decoded['name'] ?? null) !== $expectedPackage)
				{
				continue;
				}

			return \basename(\dirname($composerPath));
			}

		return null;
		}

	public static function selectedPharPathForFramework(string $framework) : ?string
		{
		$selected = self::loadSelections()[$framework] ?? null;

		if (! \is_string($selected) || $selected === '')
			{
			return null;
			}

		$path = \str_starts_with($selected, '/')
			? $selected
			: (\dirname(__DIR__) . '/dist/' . $selected);

		if (! \is_file($path))
			{
			return null;
			}

		$basename = \basename($path);
		$prefix = 'runtime-' . \strtolower($framework) . '-';

		return \str_starts_with($basename, $prefix) ? $path : null;
		}

	/**
	 * @return list<array{basename: string, path: string, version_slug: string, selected: bool, default: bool, built_at: int}>
	 */
	public static function availablePharsForFramework(string $framework) : array
		{
		$pattern = \dirname(__DIR__) . '/dist/runtime-' . \strtolower($framework) . '-*.phar';
		$files = \glob($pattern) ?: [];
		$selected = self::selectedPharPathForFramework($framework);
		$default = self::defaultPharPathForFramework($framework);
		$prefix = 'runtime-' . \strtolower($framework) . '-';
		$entries = [];

		foreach ($files as $file)
			{
			if (! \is_file($file))
				{
				continue;
				}

			$basename = \basename($file);
			$versionSlug = \substr($basename, \strlen($prefix), -5);
			$entries[] = [
				'basename' => $basename,
				'path' => $file,
				'version_slug' => $versionSlug,
				'selected' => $selected !== null && \realpath($selected) === \realpath($file),
				'default' => \realpath($default) === \realpath($file),
				'built_at' => (int)(\filemtime($file) ?: 0),
			];
			}

		\usort($entries, static fn(array $a, array $b) : int => $b['built_at'] <=> $a['built_at']);

		return $entries;
		}

	public static function selectPharForFramework(string $framework, string $path) : void
		{
		$resolved = \realpath($path);

		if ($resolved === false || ! \is_file($resolved))
			{
			throw new \RuntimeException("PHAR not found: {$path}");
			}

		$basename = \basename($resolved);
		$prefix = 'runtime-' . \strtolower($framework) . '-';

		if (! \str_starts_with($basename, $prefix))
			{
			throw new \RuntimeException("PHAR {$basename} does not belong to {$framework}");
			}

		$selections = self::loadSelections();
		$distRoot = \dirname(__DIR__) . '/dist/';
		$selections[$framework] = \str_starts_with($resolved, $distRoot) ? $basename : $resolved;
		self::saveSelections($selections);
		}

	public static function clearSelectedPharForFramework(string $framework) : void
		{
		$selections = self::loadSelections();
		unset($selections[$framework]);
		self::saveSelections($selections);
		}

	/**
	 * @return array<string, mixed>
	 */
	private static function runtimeComposerMetadata(string $runtimeDir) : array
		{
		$composerPath = $runtimeDir . '/composer.json';

		if (! \is_file($composerPath))
			{
			return [];
			}

		$decoded = \json_decode((string)\file_get_contents($composerPath), true);

		return \is_array($decoded) ? $decoded : [];
		}

	private static function runtimeVersion(string $framework, string $runtimeDir, array $composer) : string
		{
		$packageName = self::primaryPackageName($framework, $composer);

		if ($packageName === '')
			{
			return '';
			}

		$installedPath = $runtimeDir . '/vendor/composer/installed.json';

		if (\is_file($installedPath))
			{
			$decoded = \json_decode((string)\file_get_contents($installedPath), true);

			if (\is_array($decoded))
				{
				$packages = isset($decoded['packages']) && \is_array($decoded['packages'])
					? $decoded['packages']
					: $decoded;

				foreach ($packages as $package)
					{
					if (! \is_array($package) || ($package['name'] ?? null) !== $packageName)
						{
						continue;
						}

					$version = (string)($package['version'] ?? $package['pretty_version'] ?? '');
					$reference = (string)($package['source']['reference'] ?? $package['dist']['reference'] ?? '');

					if ($version !== '' && ! self::isNonVersioned($version))
						{
						return $version;
						}

					$formattedReference = self::formatReference($reference);

					if ($formattedReference !== '')
						{
						return $formattedReference;
						}

					break;
					}
				}
			}

		$fallback = FrameworkVersion::forTest($framework);

		if (\str_contains($fallback, '@'))
			{
			return (string)\substr($fallback, (int)\strrpos($fallback, '@') + 1);
			}

		if (self::isNonVersioned($fallback))
			{
			return '';
			}

		return $fallback;
		}

	private static function runtimeHomepage(string $framework, string $runtimeDir, array $composer) : string
		{
		$packageName = self::primaryPackageName($framework, $composer);

		if ($packageName === '')
			{
			return (string)($composer['homepage'] ?? '');
			}

		$installedPath = $runtimeDir . '/vendor/composer/installed.json';

		if (\is_file($installedPath))
			{
			$decoded = \json_decode((string)\file_get_contents($installedPath), true);

			if (\is_array($decoded))
				{
				$packages = isset($decoded['packages']) && \is_array($decoded['packages'])
					? $decoded['packages']
					: $decoded;

				foreach ($packages as $package)
					{
					if (! \is_array($package) || ($package['name'] ?? null) !== $packageName)
						{
						continue;
						}

					return (string)($package['homepage'] ?? $composer['homepage'] ?? '');
					}
				}
			}

		return (string)($composer['homepage'] ?? '');
		}

	private static function installedPackageCount(string $runtimeDir) : int
		{
		$installedPath = $runtimeDir . '/vendor/composer/installed.json';

		if (! \is_file($installedPath))
			{
			return 0;
			}

		$decoded = \json_decode((string)\file_get_contents($installedPath), true);

		if (! \is_array($decoded))
			{
			return 0;
			}

		$packages = isset($decoded['packages']) && \is_array($decoded['packages'])
			? $decoded['packages']
			: $decoded;

		return \count(\array_filter($packages, 'is_array'));
		}

	private static function primaryPackageName(string $framework, array $composer) : string
		{
		$map = [
			'ActiveRecord' => 'php-patterns/activerecord',
			'Cake' => 'cakephp/cakephp',
			'CakeCached' => 'cakephp/cakephp',
			'Doctrine' => 'doctrine/orm',
			'DivergenceV2' => 'divergence/divergence',
			'DivergenceV3' => 'divergence/divergence',
			'Eloquent' => 'illuminate/database',
			'PHPFUI' => 'phpfui/orm',
			'PHPFUIBatch' => 'phpfui/orm',
			'Propel2' => 'propel/propel',
			'RedBean' => 'gabordemooij/redbean',
		];

		if (isset($map[$framework]))
			{
			return $map[$framework];
			}

		$requires = $composer['require'] ?? [];

		if (! \is_array($requires))
			{
			return '';
			}

		foreach (\array_keys($requires) as $package)
			{
			if ($package !== 'php' && ! \str_starts_with((string)$package, 'ext-'))
				{
				return (string)$package;
				}
			}

		return '';
		}

	private static function isNonVersioned(string $version) : bool
		{
		$version = \strtolower(\trim($version));

		return $version === ''
			|| \str_contains($version, 'dev')
			|| \str_contains($version, 'master')
			|| \str_contains($version, '.x-');
		}

	private static function formatReference(string $reference) : string
		{
		$reference = \trim($reference);

		return $reference !== '' ? \substr($reference, 0, 8) : '';
		}

	/**
	 * @return array<string, string>
	 */
	private static function loadSelections() : array
		{
		$path = self::selectionFilePath();

		if (! \is_file($path))
			{
			return [];
			}

		$decoded = \json_decode((string)\file_get_contents($path), true);

		if (! \is_array($decoded))
			{
			return [];
			}

		$selected = $decoded['selected'] ?? $decoded;

		return \is_array($selected)
			? \array_filter($selected, static fn(mixed $value) : bool => \is_string($value) && $value !== '')
			: [];
		}

	/**
	 * @param array<string, string> $selections
	 */
	private static function saveSelections(array $selections) : void
		{
		$path = self::selectionFilePath();
		$directory = \dirname($path);

		if (! \is_dir($directory) && ! \mkdir($directory, 0777, true) && ! \is_dir($directory))
			{
			throw new \RuntimeException("Unable to create {$directory}");
			}

		\ksort($selections);
		$json = \json_encode(['selected' => $selections], \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES);

		if (! \is_string($json) || \file_put_contents($path, $json . \PHP_EOL) === false)
			{
			throw new \RuntimeException("Unable to write {$path}");
			}
		}

	private static function selectionFilePath() : string
		{
		return \dirname(__DIR__) . '/config/runtime-selection.json';
		}

	public static function pharPathFor(Configuration $config) : string
		{
		return self::pharPathForFramework($config->getNamespace());
		}

	public static function hasRuntimePhar(Configuration $config) : bool
		{
		return \is_file(self::pharPathFor($config));
		}

	public static function compileCommandFor(Configuration $config) : string
		{
		return './bin/the-php-bench runtime:build ' . $config->getNamespace() . ' --select';
		}

	public static function missingRuntimeWarning(Configuration $config) : string
		{
		return \sprintf(
			'Runtime PHAR missing for %s at %s. Skipping its benchmarks. Build it with `%s`.',
			$config->getNamespace(),
			self::pharPathFor($config),
			self::compileCommandFor($config)
		);
		}

	public static function workerBinFor(Configuration $config) : string
		{
		return self::pharPathFor($config);
		}

	public static function directWorkerBinFor(Configuration $config) : string
		{
		return self::runtimeDirForFramework($config->getNamespace()) . '/bin/worker';
		}

	public static function usesIsolatedRuntime(Configuration $config) : bool
		{
		return true;
		}
	}
