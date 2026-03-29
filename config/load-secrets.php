<?php

$sharedSecretsFile = __DIR__ . '/secrets.local.php';

if (! \file_exists($sharedSecretsFile))
	{
	return [];
	}

$sharedSecrets = include $sharedSecretsFile;

return \is_array($sharedSecrets) ? $sharedSecrets : [];
