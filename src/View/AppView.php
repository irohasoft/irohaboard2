<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use Cake\View\View;
use App\Vendor\Utils;
use Cake\Routing\Router;

/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public $action; //CakePHP2の仕様の引継ぎ
    public $webroot; //CakePHP2の仕様の引継ぎ
    
    public function initialize(): void
    {
		$this->action = $this->request->getParam('action');
		$this->webroot = Router::url('/', true);
		
		$this->loadHelper('Authentication.Identity');
		/*
		$this->Breadcrumbs->setTemplates([
			'wrapper' => '{{content}}',
			'item' => '<a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>{{separator}}',
			'itemWithoutLink' => '<span{{innerAttrs}}>{{title}}</span></li>{{separator}}',
			'separator' => ' > '
		]);
		*/
	}

	protected function readAuthUser($key)
	{
		return $this->getRequest()->getAttribute('identity')->get($key);
	}

	protected function isLogined()
	{
		return $this->Identity->isLoggedIn();
	}

	public function getAction()
	{
		return $this->request->getParam('action');
	}

	public function test()
	{
		debug('test');
	}
}
