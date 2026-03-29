<?php

declare(strict_types=1);

if ($argc < 3)
	{
	\fwrite(\STDERR, "Usage: runtime-worker <framework> <base64-encoded-config>\n");
	exit(1);
	}

$framework = (string)$argv[1];
$payload = (string)$argv[2];
$repoRoot = \dirname(__DIR__);

require_once $repoRoot . '/src/RuntimeBootstrap.php';
require_once $repoRoot . '/src/RuntimeRegistry.php';

\ThePHPBench\RuntimeBootstrap::boot($framework, \ThePHPBench\RuntimeRegistry::runtimeDirForFramework($framework), $repoRoot);
\ThePHPBench\Worker::run($payload);
