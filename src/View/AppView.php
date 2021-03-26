<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

declare(strict_types=1);

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
		$this->loadHelper('Form', ['className' => 'AppForm']);
		
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

	/**
	 * 管理画面へのアクセスかを確認
	 * @return bool true : 管理画面, false : 受講者画面
	 */
	protected function isAdminPage()
	{
		return ($this->getRequest()->getParam('prefix') == 'Admin');
	}

	/**
	 * 編集画面へのアクセスかを確認
	 */
	protected function isEditPage()
	{
		return ($this->request->getParam('action') == 'edit');
	}

	/**
	 * テスト結果画面へのアクセスかを確認
	 */
	protected function isRecordPage()
	{
		return ($this->request->getParam('action') == 'record');
	}

	/**
	 * ログイン画面へのアクセスかを確認
	 */
	protected function isLoginPage()
	{
		return ($this->request->getParam('action') == 'login');
	}

	public function test()
	{
		debug('test');
	}
}
