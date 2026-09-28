<?php

declare(strict_types=1);

namespace ThePHPBench\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'upload', description: 'Check in a runner, verify its key, and upload a signed benchmark CSV')]
class UploadResultsCommand extends Command
	{
	protected function configure() : void
		{
		$this
			->addArgument('file', InputArgument::OPTIONAL, 'Path to a results CSV file (defaults to the latest file under results/)')
			->addOption('server', null, InputOption::VALUE_REQUIRED, 'Upload API base URL', 'https://the-php-bench.technex.us')
			->addOption('runner-uuid', null, InputOption::VALUE_REQUIRED, 'Runner UUID', 'runner-1')
			->addOption('runner-name', null, InputOption::VALUE_REQUIRED, 'Runner display name', php_uname('n'))
			->addOption('public-key', null, InputOption::VALUE_REQUIRED, 'Base64url Ed25519 public key or file path')
			->addOption('private-key', null, InputOption::VALUE_REQUIRED, 'Base64url Ed25519 secret key or file path')
			->addOption('environment', null, InputOption::VALUE_REQUIRED, 'Environment label', 'local');
		}

	protected function execute(InputInterface $input, OutputInterface $output) : int
		{
		if (! \function_exists('sodium_crypto_sign_detached'))
			{
			$output->writeln('<error>The sodium extension is required for upload signing.</error>');

			return Command::FAILURE;
			}

		$file = $this->resolveResultsFile((string)($input->getArgument('file') ?? ''));

		if ($file === null)
			{
			$output->writeln('<error>No results file found.</error>');

			return Command::FAILURE;
			}

		$publicKey = $this->decodeKeyOption((string)$input->getOption('public-key'));
		$privateKey = $this->decodeKeyOption((string)$input->getOption('private-key'));

		if ($publicKey === null || $privateKey === null)
			{
			$output->writeln('<error>Both --public-key and --private-key are required.</error>');

			return Command::FAILURE;
			}

		$server = \rtrim((string)$input->getOption('server'), '/');
		$runnerUuid = (string)$input->getOption('runner-uuid');
		$runnerName = (string)$input->getOption('runner-name');
		$environment = (string)$input->getOption('environment');

		$output->writeln('<fg=gray>Upload file ' . $file . '</>');

		$checkIn = $this->postJson($server . '/api/runners/check-in', [
			'runner_uuid' => $runnerUuid,
			'name' => $runnerName,
			'public_key' => $this->base64UrlEncode($publicKey),
			'metadata' => [
				'host' => php_uname('n'),
				'environment' => $environment,
			],
		]);

		$challenge = (string)($checkIn['challenge']
			?? $checkIn['verification']['challenge']
			?? '');

		if ($challenge === '')
			{
			throw new \RuntimeException('Runner check-in did not return a challenge');
			}

		$this->postJson($server . '/api/runners/verify-key', [
			'runner_uuid' => $runnerUuid,
			'signature' => $this->base64UrlEncode(\sodium_crypto_sign_detached($challenge, $privateKey)),
		]);

		$sha256 = \hash_file('sha256', $file);
		$message = 'benchmark-sha256:' . $sha256;
		$signature = $this->base64UrlEncode(\sodium_crypto_sign_detached($message, $privateKey));

		$upload = $this->postMultipart($server . '/api/benchmarks/uploads', [
			'runner_uuid' => $runnerUuid,
			'signature' => $signature,
		], 'benchmark', $file);

		$output->writeln('<fg=green>✓ Upload complete</>');
		$output->writeln(\json_encode([
			'file' => $file,
			'sha256' => $sha256,
			'response' => $upload,
		], \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));

		return Command::SUCCESS;
		}

	private function resolveResultsFile(string $requested) : ?string
		{
		if ($requested !== '')
			{
			return \is_file($requested) ? $requested : null;
			}

		$files = \glob('results/results-*.csv') ?: [];
		$files = \array_values(\array_filter($files, 'is_file'));

		if ($files === [])
			{
			return null;
			}

		\usort($files, static fn(string $a, string $b) : int => \filemtime($b) <=> \filemtime($a));

		return $files[0];
		}

	private function decodeKeyOption(string $value) : ?string
		{
		$value = \trim($value);

		if ($value === '')
			{
			return null;
			}

		if (\is_file($value))
			{
			$value = \trim((string)\file_get_contents($value));
			}

		$decoded = $this->base64UrlDecode($value);

		return $decoded !== '' ? $decoded : null;
		}

	/**
	 * @param array<string, mixed> $payload
	 * @return array<string, mixed>
	 */
	private function postJson(string $url, array $payload) : array
		{
		$body = \json_encode($payload, \JSON_UNESCAPED_SLASHES);

		if ($body === false)
			{
			throw new \RuntimeException('Unable to encode JSON payload');
			}

		$response = $this->request($url, [
			'method' => 'POST',
			'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
			'content' => $body,
		]);

		$decoded = \json_decode($response, true);

		if (! \is_array($decoded))
			{
			throw new \RuntimeException('Invalid JSON response from ' . $url);
			}

		return $decoded;
		}

	/**
	 * @param array<string, string> $fields
	 * @return array<string, mixed>
	 */
	private function postMultipart(string $url, array $fields, string $fileField, string $filePath) : array
		{
		$boundary = '--------------------------' . \bin2hex(\random_bytes(12));
		$eol = "\r\n";
		$body = '';

		foreach ($fields as $name => $value)
			{
			$body .= '--' . $boundary . $eol;
			$body .= 'Content-Disposition: form-data; name="' . $name . '"' . $eol . $eol;
			$body .= $value . $eol;
			}

		$body .= '--' . $boundary . $eol;
		$body .= 'Content-Disposition: form-data; name="' . $fileField . '"; filename="' . \basename($filePath) . '"' . $eol;
		$body .= 'Content-Type: text/csv' . $eol . $eol;
		$body .= (string)\file_get_contents($filePath) . $eol;
		$body .= '--' . $boundary . '--' . $eol;

		$response = $this->request($url, [
			'method' => 'POST',
			'header' => "Content-Type: multipart/form-data; boundary={$boundary}\r\nAccept: application/json\r\n",
			'content' => $body,
		]);

		$decoded = \json_decode($response, true);

		if (! \is_array($decoded))
			{
			throw new \RuntimeException('Invalid JSON response from ' . $url);
			}

		return $decoded;
		}

	/**
	 * @param array<string, mixed> $http
	 */
	private function request(string $url, array $http) : string
		{
		$context = \stream_context_create([
			'http' => $http + [
				'ignore_errors' => true,
				'timeout' => 30,
			],
		]);

		$result = @\file_get_contents($url, false, $context);

		if ($result === false)
			{
			throw new \RuntimeException('HTTP request failed for ' . $url);
			}

		return $result;
		}

	private function base64UrlEncode(string $binary) : string
		{
		return \rtrim(\strtr(\base64_encode($binary), '+/', '-_'), '=');
		}

	private function base64UrlDecode(string $value) : string
		{
		$padding = \strlen($value) % 4;

		if ($padding > 0)
			{
			$value .= \str_repeat('=', 4 - $padding);
			}

		return \base64_decode(\strtr($value, '-_', '+/'), true) ?: '';
		}
	}
