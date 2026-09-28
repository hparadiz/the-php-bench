<?php

declare(strict_types=1);

namespace ThePHPBench;

final class MachineInfo
	{
	private const MANIFEST_FILE = __DIR__ . '/../system-manifest.json';

	/** @var array<string, int|float|string> */
	private static array $cache = [];

	/**
	 * @return array<string, int|float|string>
	 */
	public static function all() : array
		{
		if (self::$cache !== [])
			{
			return self::$cache;
			}

		return self::$cache = self::loadManifest() + self::baselineInfo();
		}

	/**
	 * @return array<string, int|float|string|bool>
	 */
	public static function generateManifest() : array
		{
		return \array_replace(self::baselineInfo(), [
			'machine_memory_modules' => self::memoryModules(),
			'system_manifest_version' => '1',
			'system_manifest_generated_at' => \date('c'),
		]);
		}

	/**
	 * @return array<string, int|float|string|bool>
	 */
	public static function writeManifest(?string $path = null) : array
		{
		$path ??= self::manifestPath();
		$data = self::generateManifest();
		$json = \json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

		if (! \is_string($json))
			{
			throw new \RuntimeException('Failed to encode system manifest');
			}

		if (\file_put_contents($path, $json . "\n") === false)
			{
			throw new \RuntimeException("Failed to write {$path}");
			}

		self::restoreManifestOwnership($path);

		self::$cache = [];

		return $data;
		}

	public static function manifestPath() : string
		{
		return self::MANIFEST_FILE;
		}

	/**
	 * @return array<string, int|float|string|bool>
	 */
	private static function loadManifest() : array
		{
		$file = self::manifestPath();

		if (! \is_file($file))
			{
			return [];
			}

		$decoded = \json_decode((string)\file_get_contents($file), true);

		return \is_array($decoded) ? $decoded : [];
		}

	/**
	 * @return array<string, int|float|string|bool>
	 */
	private static function baselineInfo() : array
		{
		$uname = \php_uname();
		$host = (string)(\gethostname() ?: '');

		return [
			'machine_host' => $host,
			'machine_os' => \php_uname('s'),
			'machine_kernel' => \php_uname('r'),
			'machine_arch' => \php_uname('m'),
			'machine_php' => \PHP_VERSION,
			'machine_system' => $uname,
			'machine_cpu_model' => self::cpuModel(),
			'machine_cpu_cores' => self::cpuCores(),
			'machine_memory_bytes' => self::memoryBytes(),
			'machine_memory_modules' => '',
			'machine_boot_disk' => self::bootDisk(),
			'machine_disk_bytes' => self::diskBytes(),
		];
		}

	private static function cpuModel() : string
		{
		$cpuinfo = @\file('/proc/cpuinfo', \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

		if (\is_array($cpuinfo))
			{
			foreach ($cpuinfo as $line)
				{
				if (\str_starts_with($line, 'model name'))
					{
					[, $value] = \array_pad(\explode(':', $line, 2), 2, '');

					return \trim($value);
					}
				}
			}

		return '';
		}

	private static function cpuCores() : int
		{
		$cpuinfo = @\file('/proc/cpuinfo', \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

		if (\is_array($cpuinfo))
			{
			$count = 0;

			foreach ($cpuinfo as $line)
				{
				if (\str_starts_with($line, 'processor'))
					{
					++$count;
					}
				}

			if ($count > 0)
				{
				return $count;
				}
			}

		return 0;
		}

	private static function memoryBytes() : int
		{
		$meminfo = @\file('/proc/meminfo', \FILE_IGNORE_NEW_LINES | \FILE_SKIP_EMPTY_LINES);

		if (\is_array($meminfo))
			{
			foreach ($meminfo as $line)
				{
				if (\str_starts_with($line, 'MemTotal:'))
					{
					if (\preg_match('/^MemTotal:\s+(\d+)\s+kB$/', $line, $matches))
						{
						return (int)$matches[1] * 1024;
						}
					}
				}
			}

		return 0;
		}

	private static function diskBytes() : float
		{
		$total = @\disk_total_space(\getcwd() ?: '.');

		return \is_numeric($total) ? (float)$total : 0.0;
		}

	private static function bootDisk() : string
		{
		$source = \trim((string)@\shell_exec('findmnt -n -o SOURCE /'));

		if ($source === '')
			{
			return '';
			}

		$source = \strtok($source, " \t\n") ?: $source;
		$device = self::resolveBlockParent(\basename($source));

		if ($device !== '')
			{
			return '/dev/' . $device;
			}

		return $source;
		}

	private static function memoryModules() : string
		{
		$lines = [];
		@\exec('sudo -n dmidecode -t memory 2>/dev/null || dmidecode -t memory 2>/dev/null', $lines);

		if ($lines === [])
			{
			return '';
			}

		$devices = [];
		$inDevice = false;
		$slot = '';
		$size = '';
		$speed = '';
		$type = '';
		$part = '';

		$flush = static function() use (&$devices, &$slot, &$size, &$speed, &$type, &$part) : void
			{
			if ($slot === '' || $size === '' || $size === 'No Module Installed')
				{
				$slot = $size = $speed = $type = $part = '';

				return;
				}

			$pieces = \array_values(\array_filter([$slot, $size, $speed, $type, $part]));

			if ($pieces !== [])
				{
				$devices[] = \implode(' ', $pieces);
				}

			$slot = $size = $speed = $type = $part = '';
			};

		foreach ($lines as $line)
			{
			$trimmed = \trim($line);

			if ($trimmed === 'Memory Device')
				{
				if ($inDevice)
					{
					$flush();
					}

				$inDevice = true;
				continue;
				}

			if (! $inDevice)
				{
				continue;
				}

			if ($trimmed === '')
				{
				$flush();
				$inDevice = false;
				continue;
				}

			if (\preg_match('/^\s*Locator:\s*(.+)$/', $line, $matches))
				{
				$slot = \trim($matches[1]);
				continue;
				}

			if (\preg_match('/^\s*Size:\s*(.+)$/', $line, $matches))
				{
				$size = \trim($matches[1]);
				continue;
				}

			if (\preg_match('/^\s*Configured Memory Speed:\s*(.+)$/', $line, $matches))
				{
				$speed = \trim($matches[1]);
				continue;
				}

			if ($speed === '' && \preg_match('/^\s*Speed:\s*(.+)$/', $line, $matches))
				{
				$speed = \trim($matches[1]);
				continue;
				}

			if (\preg_match('/^\s*Type:\s*(.+)$/', $line, $matches))
				{
				$type = \trim($matches[1]);
				continue;
				}

			if (\preg_match('/^\s*Part Number:\s*(.+)$/', $line, $matches))
				{
				$part = \trim($matches[1]);
				}
			}

		if ($inDevice)
			{
			$flush();
			}

		return \implode(' | ', $devices);
		}

	private static function resolveBlockParent(string $name) : string
		{
		if ($name === '')
			{
			return '';
			}

		$path = '/sys/class/block/' . $name;

		if (! \file_exists($path))
			{
			return '';
			}

		$resolved = \realpath($path . '/..');

		if (! \is_string($resolved) || $resolved === '')
			{
			return $name;
			}

		$device = \basename($resolved);

		if ($device !== '' && \str_starts_with($device, 'nvme'))
			{
			return $device;
			}

		if ($device !== '' && $device !== '.' && $device !== '..')
			{
			return $device;
			}

		return $name;
		}

	private static function restoreManifestOwnership(string $path) : void
		{
		$sudoUid = \getenv('SUDO_UID');
		$sudoGid = \getenv('SUDO_GID');

		if ($sudoUid !== false && $sudoUid !== '')
			{
			@\chown($path, (int)$sudoUid);
			}

		if ($sudoGid !== false && $sudoGid !== '')
			{
			@\chgrp($path, (int)$sudoGid);
			}
		}
	}
