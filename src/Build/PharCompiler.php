<?php

declare(strict_types=1);

namespace ThePHPBench\Build;

final class PharCompiler
	{
	/**
	 * @param array<string, mixed> $manifest
	 */
	public static function build(array $manifest, string $outputPath) : string
		{
		if (\ini_get('phar.readonly'))
			{
			throw new \RuntimeException('phar.readonly is enabled. Run with -d phar.readonly=0');
			}

		$outputPath = self::normalizeOutputPath($outputPath);

		if (\file_exists($outputPath) && ! \unlink($outputPath))
			{
			throw new \RuntimeException("Unable to overwrite {$outputPath}");
			}

		$root = \dirname(__DIR__, 2);
		$phar = new \Phar($outputPath, 0, \basename($outputPath));
		$phar->startBuffering();
		$phar->setSignatureAlgorithm(\Phar::SHA512);

		$excludePrefixes = \array_values(\array_map(
			static fn(string $path) : string => \str_replace('\\', '/', $root . '/' . \ltrim($path, '/')),
			(array)($manifest['exclude_local_prefixes'] ?? [])
		));

		foreach (($manifest['mappings'] ?? []) as $local => $archive)
			{
			foreach ((array)$archive as $archiveTarget)
				{
				self::debug("packing {$local} -> {$archiveTarget}");
				self::addPath($phar, $root . '/' . $local, (string)$archiveTarget, $excludePrefixes);
				}
			}

		$meta = $manifest;
		unset($meta['mappings']);
		$phar['build-manifest.json'] = \json_encode($meta, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES) ?: '{}';
		$phar->setStub(self::stub($manifest));
		$phar->stopBuffering();

		return $outputPath;
		}

	private static function debug(string $message) : void
		{
		if (\getenv('THEPHPBENCH_PHAR_DEBUG') !== '1')
			{
			return;
			}

		\fwrite(\STDERR, '[phar] ' . $message . \PHP_EOL);
		}

	/**
	 * @return list<string>
	 */
	public static function packagedFiles(string $pharPath) : array
		{
		$phar = new \Phar($pharPath);
		$files = [];
		$iterator = new \RecursiveIteratorIterator($phar);

		foreach ($iterator as $file)
			{
			$files[] = \str_replace('\\', '/', $file->getPathName());
			}

		\sort($files);

		return $files;
		}

	private static function normalizeOutputPath(string $outputPath) : string
		{
		if (! \str_ends_with($outputPath, '.phar'))
			{
			$outputPath .= '.phar';
			}

		$directory = \dirname($outputPath);

		if (! \is_dir($directory) && ! \mkdir($directory, 0777, true) && ! \is_dir($directory))
			{
			throw new \RuntimeException("Unable to create {$directory}");
			}

		return $outputPath;
		}

	private static function addPath(\Phar $phar, string $localPath, string $archivePath, array $excludePrefixes = []) : void
		{
		$normalizedLocalPath = \str_replace('\\', '/', $localPath);

		foreach ($excludePrefixes as $excludePrefix)
			{
			if ($normalizedLocalPath === $excludePrefix || \str_starts_with($normalizedLocalPath, $excludePrefix . '/'))
				{
				return;
				}
			}

		if (! \file_exists($localPath) && ! \is_link($localPath))
			{
			return;
			}

		if (\is_link($localPath))
			{
			$resolved = \realpath($localPath);

			if ($resolved === false)
				{
				throw new \RuntimeException("Broken symlink: {$localPath}");
				}

			self::addPath($phar, $resolved, $archivePath, $excludePrefixes);

			return;
			}

		if (\is_file($localPath))
			{
			$phar->addFile($localPath, $archivePath);

			return;
			}

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($localPath, \RecursiveDirectoryIterator::SKIP_DOTS),
			\RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($iterator as $item)
			{
			$pathName = \str_replace('\\', '/', $item->getPathname());

			foreach ($excludePrefixes as $excludePrefix)
				{
				if ($pathName === $excludePrefix || \str_starts_with($pathName, $excludePrefix . '/'))
					{
					continue 2;
					}
				}

			$relative = \ltrim(\substr($pathName, \strlen(\str_replace('\\', '/', $localPath))), '/');
			$target = \rtrim($archivePath, '/') . ($relative !== '' ? '/' . $relative : '');

			if ($item->isLink())
				{
				$resolved = \realpath($item->getPathname());

				if ($resolved === false)
					{
					throw new \RuntimeException("Broken symlink: {$pathName}");
					}

				self::addPath($phar, $resolved, $target, $excludePrefixes);

				continue;
				}

			if ($item->isFile())
				{
				$phar->addFile($item->getPathname(), $target);
				}
			}
		}

	/**
	 * @param array<string, mixed> $manifest
	 */
	private static function stub(array $manifest) : string
		{
		$type = (string)($manifest['type'] ?? 'bundle');
		$entrypoint = (string)($manifest['entrypoint'] ?? 'bin/the-php-bench');
		$framework = (string)($manifest['framework'] ?? '');

		$lines = [
			'<?php',
			'Phar::mapPhar();',
			'$pharFile = $_SERVER["argv"][0] ?? "";',
			'if ($pharFile === "") {',
			'    $pharFile = Phar::running(false);',
			'}',
			'if ($pharFile === "") {',
			'    if (preg_match("#^phar://(.+?\\.phar)(?:/.*)?$#", __FILE__, $matches)) {',
			'        $pharFile = $matches[1];',
			'    } else {',
			'        $pharFile = __FILE__;',
			'    }',
			'}',
			'$extractDir = sys_get_temp_dir() . "/the-php-bench-phar/" . sha1_file($pharFile);',
			'if (!is_file($extractDir . "/.ready")) {',
			'    if (!is_dir($extractDir) && !mkdir($extractDir, 0777, true) && !is_dir($extractDir)) {',
			'        fwrite(STDERR, "Unable to create extraction directory: {$extractDir}\n");',
			'        exit(1);',
			'    }',
			'    $archive = new Phar($pharFile);',
			'    $archive->extractTo($extractDir, null, true);',
			'    @touch($extractDir . "/.ready");',
			'}',
			'chdir($extractDir);',
		];

		if ($type === 'runtime')
			{
			$lines[] = '$argv = $_SERVER["argv"];';
			$lines[] = '$raw = $argv[1] ?? "";';
			$lines[] = '$decodedPayload = json_decode(base64_decode($raw, true) ?: "", true);';
			$lines[] = 'if (is_array($decodedPayload) && isset($decodedPayload["test"])) {';
			$lines[] = '    $payload = $raw;';
			$lines[] = '} else {';
			$lines[] = '    require_once $extractDir . "/src/PharSelector.php";';
			$lines[] = '    $offset = in_array($raw, ["benchmark", "run"], true) ? 2 : 1;';
			$lines[] = '    $payload = \ThePHPBench\PharSelector::runtimePayload(' . \var_export($framework, true) . ', array_slice($argv, $offset));';
			$lines[] = '}';
			$lines[] = '$argv = [$argv[0] ?? "runtime.phar", ' . \var_export($framework, true) . ', $payload];';
			$lines[] = '$_SERVER["argv"] = $argv;';
			$lines[] = '$argc = count($argv);';
			}
		elseif (\in_array($type, ['controller', 'bundle'], true))
			{
			$lines[] = '$argv = $_SERVER["argv"];';
			$lines[] = '$command = $argv[1] ?? "";';
			$lines[] = '$knownCommands = ["benchmark", "results", "upload", "seed-dump", "seed-dump-all", "seed-preview", "list", "help", "_complete", "completion"];';
			$lines[] = 'if ($command !== "" && $command[0] !== "-" && !str_contains($command, ":") && !in_array($command, $knownCommands, true)) {';
			$lines[] = '    $argv = array_merge([$argv[0] ?? "the-php-bench.phar", "benchmark"], array_slice($argv, 1));';
			$lines[] = '    $_SERVER["argv"] = $argv;';
			$lines[] = '    $argc = count($argv);';
			$lines[] = '}';
			}

		$lines[] = 'require $extractDir . ' . \var_export('/' . $entrypoint, true) . ';';
		$lines[] = '__HALT_COMPILER();';

		return \implode("\n", $lines);
		}
	}
