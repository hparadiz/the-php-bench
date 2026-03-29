<?php

declare(strict_types=1);

namespace ThePHPBench;

final class RuntimeBootstrap
	{
	public static function boot(string $framework, string $runtimeRoot, string $repoRoot) : void
		{
		\chdir($repoRoot);

		$autoload = self::vendorAutoloadPath($framework, $runtimeRoot, $repoRoot);

		if (! \is_file($autoload))
			{
			throw new \RuntimeException("Runtime vendor autoload not found for {$framework}: {$autoload}");
			}

		require_once $autoload;
		self::bootstrapFramework($framework, $runtimeRoot);

		if (! \function_exists('base_path'))
			{
			function base_path(string $path = '') : string
				{
				$root = \dirname(__DIR__);

				return '' === $path ? $root : $root . '/' . $path;
				}
			}

		self::registerBenchmarkAutoloader($repoRoot);
		self::registerFrameworkSourceAutoloader($framework, $runtimeRoot, $repoRoot);
		}

	private static function vendorAutoloadPath(string $framework, string $runtimeRoot, string $repoRoot) : string
		{
		return $runtimeRoot . '/vendor/autoload.php';
		}

	private static function bootstrapFramework(string $framework, string $runtimeRoot) : void
		{
		match ($framework) {
			'Yii' => self::bootstrapYii($runtimeRoot),
			default => null,
		};
		}

	private static function bootstrapYii(string $runtimeRoot) : void
		{
		if (\class_exists(\Yii::class, false))
			{
			return;
			}

		$yiiBootstrap = $runtimeRoot . '/vendor/yiisoft/yii2/Yii.php';

		if (! \is_file($yiiBootstrap))
			{
			throw new \RuntimeException("Yii bootstrap not found: {$yiiBootstrap}");
			}

		require_once $yiiBootstrap;
		}

	private static function registerBenchmarkAutoloader(string $repoRoot) : void
		{
		\spl_autoload_register(static function(string $class) use ($repoRoot) : void
			{
			$prefix = 'ThePHPBench\\';

			if (! \str_starts_with($class, $prefix))
				{
				return;
				}

			$relative = \substr($class, \strlen($prefix));
			$file = $repoRoot . '/src/' . \str_replace('\\', '/', $relative) . '.php';

			if (\is_file($file))
				{
				require_once $file;
				}
			});
		}

	private static function registerFrameworkSourceAutoloader(string $framework, string $runtimeRoot, string $repoRoot) : void
		{
		match ($framework) {
			'DivergenceV2' => self::registerPrefixAutoloader('Divergence\\', [
				$runtimeRoot . '/vendor/divergence/divergence/src',
			]),
			'DivergenceV3' => self::registerPrefixAutoloader('Divergence\\', [
				$runtimeRoot . '/vendor/divergence/divergence/src',
			]),
			default => null,
		};
		}

	/**
	 * @param list<string> $roots
	 */
	private static function registerPrefixAutoloader(string $prefix, array $roots) : void
		{
		\spl_autoload_register(static function(string $class) use ($prefix, $roots) : void
			{
			if (! \str_starts_with($class, $prefix))
				{
				return;
				}

			$relative = \str_replace('\\', '/', \substr($class, \strlen($prefix))) . '.php';

			foreach ($roots as $root)
				{
				$file = $root . '/' . $relative;

				if (\is_file($file))
					{
					require_once $file;

					return;
					}
				}
			});
		}
	}
