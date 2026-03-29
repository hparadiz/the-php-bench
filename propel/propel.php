<?php

return [
	'propel' => [
		'paths' => [
			'schemaDir' => __DIR__,
			'phpDir' => '/tmp/php-orm-propel-build',
		],
		'database' => [
			'connections' => [
				'default' => [
					'adapter' => 'sqlite',
					'dsn' => 'sqlite::memory:',
					'user' => '',
					'password' => '',
					'settings' => [
						'charset' => 'utf8',
					],
				],
			],
		],
	],
];
