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

	/**
	 * セッションの取得
	 * @param string $key キー
	 * @return string 指定したキーの値
	 */
	protected function readSession($key)
	{
		return $this->getRequest()->getSession()->read($key);
	}

	/**
	 * セッションの削除
	 * @param string $key キー
	 */
	protected function deleteSession($key)
	{
		$this->getRequest()->getSession()->delete($key);
	}

	/**
	 * セッションの存在確認
	 * @param string $key キー
     * @return bool true : 存在する, false : 存在しない
	 */
	protected function hasSession($key)
	{
		$val = $this->getRequest()->getSession()->read($key);
		
		return ($val != null);
	}

	/**
	 * セッションの保存
	 * @param string $key キー
	 * @param string $value 値
	 */
	protected function writeSession($key, $value)
	{
		$this->getRequest()->getSession()->write($key, $value);
	}

	/**
	 * ログインユーザ情報の取得
	 * @param string $key キー
	 * @return string 指定したキーの値
	 */
	protected function readAuthUser($key = null)
	{
		return $this->getRequest()->getAttribute('identity')->get($key);
	}

	/**
	 * ログイン確認
	 * @return bool true : ログイン済み, false : ログインしていない
	 */
	protected function isLogined()
	{
		if(!$this->getRequest()->getAttribute('identity'))
			return false;
		
		return $this->getRequest()->getAttribute('identity')->get('id') > 0;
	}

	protected function readCookie($key)
	{
		return $this->getRequest()->getCookie($key);
	}

	protected function deleteCookie($key)
	{
		$cookie = new Cookie($key);
		$this->response = $this->response->withExpiredCookie($cookie);
	}

	protected function writeCookie($key, $value)
	{
		/*
		$this->Cookie->configKey('User', [
			'expires' => '+2 weeks',
			'httponly' => true
		]);
		exit;
		*/
		
		/*
		//クッキーに書き込み設定を作る
		//とりあえずデフォルト設定でキーと値だけ渡す
		$this->response = $this->response->withCookie(new Cookie('key', 'value'));

		//パラメータを渡す場合
		$cookie = (new Cookie($key))
			->withValue($value)  //value
			->withExpiry(new Time('+2 weeks'))  //タイムアウト
//			->withPath('/')
//			->withDomain('example.com')  //ドメインを指定する場合
			->withSecure(false);
//			->withHttpOnly(true);  //HTTPSだけにする

		//クッキーの書き込み
		$this->response = $this->response->withCookie($cookie);
		*/
	}

	/**
	 * クエリストリングの取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getQuery($key, $default = '')
	{
		$val = $this->getRequest()->getQuery($key);
		
		if($val == null)
			return $default;
		
		return $val;
	}

	/**
	 * クエリストリングの存在確認
	 * @param string $key キー
	 * @return bool true : 存在する, false : 存在しない
	 */
	protected function hasQuery($key)
	{
		$val = $this->getRequest()->getQuery($key);
		
		return ($val != null);
	}

	/**
	 * ルート要素とリクエストパラメータを取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getParam($key, $default = '')
	{
		$val = $this->getRequest()->getParam($key);

		if($val == null)
			return $default;
		
		return $val;
	}

	/**
	 * POSTデータの取得
	 * @param string $key キー
	 * @param string $default キーが存在しない場合に返す値
	 */
	protected function getData($key = null, $default = null)
	{
		$val = $this->getRequest()->getData();
		
		if(!$val)
			return $default;
		
		if($key)
			$val = empty($val[$key]) ? $default :$val[$key];
		
		return $val;
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
		return (($this->request->getParam('action') == 'record') || ($this->request->getParam('action') == 'adminRecord'));
	}

	/**
	 * 管理者向けテスト結果画面へのアクセスかを確認
	 */
	protected function isAdminRecordPage()
	{
		return ($this->request->getParam('action') == 'adminRecord');
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
