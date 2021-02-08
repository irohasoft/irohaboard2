<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link	  https://cakephp.org CakePHP(tm) Project
 * @since	  3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router; // add 2020.6.7

// add 2020.6.7
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Cake\Http\Middleware\EncryptedCookieMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
//class Application extends BaseApplication
// edit 2020.6.7
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
	/**
	 * Load all the application configuration and bootstrap logic.
	 *
	 * @return void
	 */
	public function bootstrap(): void
	{
		// Call parent to load bootstrap from files.
		parent::bootstrap();

		// edit 2020.10.6
		$this->addPlugin('Authentication');
		
		// 検索プラグイン
		$this->addPlugin("Search");

		if (PHP_SAPI === 'cli') {
			$this->bootstrapCli();
		}

		/*
		 * Only try to load DebugKit in development mode
		 * Debug Kit should not be installed on a production system
		 */
		if (Configure::read('debug')) {
			$this->addPlugin('DebugKit');
		}

		// Load more plugins here
	}

	/**
	 * Setup the middleware queue your application will use.
	 *
	 * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
	 * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
	 */
	public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
	{

		$middlewareQueue
			// Catch any exceptions in the lower layers,
			// and make an error page/response
			->add(new ErrorHandlerMiddleware(Configure::read('Error')))

			// Handle plugin/theme assets like CakePHP normally does.
			->add(new AssetMiddleware([
				'cacheTime' => Configure::read('Asset.cacheTime'),
			]))

			// Add routing middleware.
			// If you have a large number of routes connected, turning on routes
			// caching in production could improve performance. For that when
			// creating the middleware instance specify the cache config name by
			// using it's second constructor argument:
			// `new RoutingMiddleware($this, '_cake_routes_')`
			->add(new RoutingMiddleware($this))

			// Parse various types of encoded request bodies so that they are
			// available as array through $request->getData()
			// https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
			->add(new BodyParserMiddleware())

			// Cross Site Request Forgery (CSRF) Protection Middleware
			// https://book.cakephp.org/4/en/controllers/middleware.html#cross-site-request-forgery-csrf-middleware
			->add(new CsrfProtectionMiddleware([
				'httponly' => true,
			]))
			->add(new EncryptedCookieMiddleware(
				// 保護するクッキーの名前
				['CookieAuth'],
				Configure::read('Security.salt')
			))
			->add(new AuthenticationMiddleware($this)); // add 2020.10.6
		return $middlewareQueue;
	}

	/**
	 * Bootrapping for CLI application.
	 *
	 * That is when running commands.
	 *
	 * @return void
	 */
	protected function bootstrapCli(): void
	{
		try {
			$this->addPlugin('Bake');
		} catch (MissingPluginException $e) {
			// Do not halt if the plugin is missing
		}

		$this->addPlugin('Migrations');

		// Load more plugins here
	}

	// add 2020.10.6
	public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
	{
		$service = new AuthenticationService();

		// 認証されていない場合にユーザーがどこにリダイレクトするかを定義
		$login_url = ($request->getParam('prefix')=='Admin') ? Router::url('/admin/users/login') : Router::url('/users/login');
		
		$service->setConfig([
			'unauthenticatedRedirect' => $login_url,
			'queryParam' => 'redirect',
		]);

		$fields = [
			IdentifierInterface::CREDENTIAL_USERNAME => 'username',
			IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
		];
		
		// 認証機能の読み込み。セッションを優先
		$service->loadAuthenticator('Authentication.Session');
		$service->loadAuthenticator('Authentication.Form', [
			'fields' => $fields,
			'loginUrl' => $login_url
		]);
		
		// If the user is on the login page, check for a cookie as well.
		$service->loadAuthenticator('Authentication.Cookie', [
			'fields' => $fields,
			'loginUrl' => $login_url,
			'cookie' => [
				'name' => 'CookieAuth',
				'expires' => '+2 weeks',
				'path' => '/',
				'domain' => '',
				'secure' => false,
				'httponly' => true,
			],
		]);
		
		// Load identifiers
		//$service->loadIdentifier('Authentication.Password', compact('fields'));
		// custom 2020.06.07
		// CakePHP2 からの移行の為、SHA-1にてパスワード認証を行う
		$service->loadIdentifier('Authentication.Password', [
			// Other config options
			'passwordHasher' => [
				'className' => 'Authentication.Fallback',
				'hashers' => [
					'Authentication.Default',
					[
						'className' => 'Authentication.Legacy',
//						'hashType' => 'md5',
						'hashType' => 'sha1',
						'salt' => true // turn off default usage of salt
					],
				]
			]
		]);
		return $service;
	}
}
