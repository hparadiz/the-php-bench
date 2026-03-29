<?php

declare(strict_types=1);

namespace ThePHPBench;

final class FrameworkVersion
	{
	/** @var array<string, string> */
	/** @var array<string, string> */
	private static array $cache = [];

	/** @var array<string, array{version: string, reference: string}>|null */
	private static ?array $lockPackageCache = null;

	public static function forTest(string $test) : string
		{
		if (isset(self::$cache[$test]))
			{
			return self::$cache[$test];
			}

		$releaseVersion = FrameworkCatalog::releaseVersion($test);

		if ($releaseVersion !== '')
			{
			return self::$cache[$test] = $releaseVersion;
			}

		$package = FrameworkCatalog::packageName($test);

		if ($package === '' || ! class_exists(\Composer\InstalledVersions::class) || ! \Composer\InstalledVersions::isInstalled($package))
			{
			$fromLock = self::fromComposerLock($package !== '' ? $package : null);

			return self::$cache[$test] = $fromLock;
			}

		$version = (string)(\Composer\InstalledVersions::getPrettyVersion($package) ?? '');
		$reference = (string)(\Composer\InstalledVersions::getReference($package) ?? '');

		if ($version === '')
			{
			return self::$cache[$test] = self::formatReference($reference);
			}

		if (self::isDevelopmentVersion($version))
			{
			$formattedReference = self::formatReference($reference);

			return self::$cache[$test] = $formattedReference !== ''
				? sprintf('%s@%s', $version, $formattedReference)
				: $version;
			}

		return self::$cache[$test] = $version;
		}

	public static function label(string $test, ?string $version = null) : string
		{
		$version ??= self::forTest($test);
		$name = DisplayName::forFramework($test);

		return $version !== ''
			? sprintf('%s %s', $name, $version)
			: $name;
		}

	private static function isDevelopmentVersion(string $version) : bool
		{
		return \str_contains($version, 'dev')
			|| \str_contains($version, 'master')
			|| \str_contains($version, '.x-');
		}

	private static function formatReference(string $reference) : string
		{
		$reference = \trim($reference);

		return $reference !== '' ? \substr($reference, 0, 8) : '';
		}

	private static function fromComposerLock(?string $package) : string
		{
		if ($package === null)
			{
			return '';
			}

		$packages = self::lockPackages();

		if (! isset($packages[$package]))
			{
			return '';
			}

		$version = $packages[$package]['version'];
		$reference = $packages[$package]['reference'];

		if ($version === '')
			{
			return self::formatReference($reference);
			}

		if (self::isDevelopmentVersion($version))
			{
			$formattedReference = self::formatReference($reference);

			return $formattedReference !== ''
				? sprintf('%s@%s', $version, $formattedReference)
				: $version;
			}

		return $version;
		}

	/**
	 * @return array<string, array{version: string, reference: string}>
	 */
	private static function lockPackages() : array
		{
		if (self::$lockPackageCache !== null)
			{
			return self::$lockPackageCache;
			}

		$lockPath = \dirname(__DIR__) . '/composer.lock';

		if (! \is_file($lockPath))
			{
			return self::$lockPackageCache = [];
			}

		$decoded = \json_decode((string)\file_get_contents($lockPath), true);

		if (! \is_array($decoded))
			{
			return self::$lockPackageCache = [];
			}

		$entries = [];

		foreach (['packages', 'packages-dev'] as $section)
			{
			foreach (($decoded[$section] ?? []) as $package)
				{
				if (! \is_array($package) || ! isset($package['name']))
					{
					continue;
					}

				$entries[(string)$package['name']] = [
					'version' => (string)($package['version'] ?? ''),
					'reference' => (string)($package['source']['reference'] ?? $package['dist']['reference'] ?? ''),
				];
				}
			}

		return self::$lockPackageCache = $entries;
		}
	}
