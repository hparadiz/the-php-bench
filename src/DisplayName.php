<?php

declare(strict_types=1);

namespace ThePHPBench;

final class DisplayName
	{
	public static function forFramework(string $framework) : string
		{
		return FrameworkCatalog::displayName($framework);
		}
	}
