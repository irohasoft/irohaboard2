<?php
/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */
return [
	// デバッグモードの設定
//	'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

	// Security.salt の設定（旧バージョンの引き継ぎ）
	'Security' => [
		'salt' => env('SECURITY_SALT', '397110e45242a23e5802e78f4eec95a7bd39e0f0'),
	],

	/*
	 * メールサーバの設定
	 */
	'EmailTransport' => [
		'default' => [
			'host' => 'localhost',
			'port' => 25,
			'username' => null,
			'password' => null,
			'client' => null,
			'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
		],
	],
];
