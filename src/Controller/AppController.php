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

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use DateTime;
use App\Vendor\Utils;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    public $action; //CakePHP2の仕様の引継ぎ
    public $webroot; //CakePHP2の仕様の引継ぎ
    
	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('FormProtection');`
	 *
	 * @return void
	 */
	public function initialize(): void
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');

		// add 2020.10.6
		$this->loadComponent('Authentication.Authentication');

		/*
		 * Enable the following component for recommended CakePHP form protection settings.
		 * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
		 */
		//$this->loadComponent('FormProtection');
		$this->action = $this->request->getParam('action');
		$this->webroot = Router::url('/', true);
	}

	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		//$this->set('loginedUser', $this->readSession('Auth'));
		$this->set('loginedUser', $this->getRequest()->getAttribute('identity'));
		
		// 他のサイトの設定が存在する場合、設定情報及びログイン情報をクリア
		if($this->hasSession('Setting'))
		{
			if($this->readSession('Setting.app_dir') != APP_DIR)
			{
				// セッション内の設定情報を削除
				$this->deleteSession('Setting');
				
				// 他のサイトとのログイン情報の混合を避けるため、強制ログアウト
				if($this->readAuthUser())
				{
					//$this->Cookie->delete('Auth');
					$this->redirect($this->Auth->logout());
					return;
				}
			}
		}

		// データベース内に格納された設定情報をセッションに格納
		if(!$this->hasSession('Setting'))
		{
			$this->loadModel('Settings');
			$settings = $this->Settings->getSettings();
			
			$this->writeSession('Setting.app_dir', APP_DIR);
			
			foreach($settings as $key => $value)
			{
				$this->writeSession('Setting.'.$key, $value);
			}
		}
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
			'httpOnly' => true
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
	 * @return string 指定したキーの値
	 */
	protected function getQuery($key)
	{
		$val = $this->getRequest()->getQuery($key);
		
		if($val == '')
			return null;
		
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
	 * POSTデータの取得
	 * @param string $key キー
	 * @return string 指定したキーの値（省略した場合、全て）
	 */
	protected function getData($key = null)
	{
		$val = $this->getRequest()->getData();
		
		if(!$val)
			return null;
		
		if($key)
			$val = empty($val[$key]) ? null :$val[$key];
		
		return $val;
	}

	/**
	 * 条件の追加（検索キーの値が指定されている場合のみ）
	 * @param string $where 条件
	 * @param string $key キー
	 * @param string $field 対象フィールド
	 * @return string $where 新しい条件
	 */
	protected function addCondition($where, $key, $field)
	{
		$val = $this->getQuery($key);
		
		if(!$val)
			return $where;
		
		if(strpos(strtolower($field), 'like') > 0)
		{
			$where[$field] = '%'.$val.'%';
		}
		else
		{
			$where[$field] = $val;
		}
		
		return $where;
	}

	/**
	 * 管理画面のアクセスか確認
	 * @return bool true : 管理画面, false : 受講者画面
	 */
	protected function isAdminPage()
	{
		return ($this->getRequest()->getParam('prefix') == 'Admin');
	}

	/**
	 * ログの保存
	 * @param string $log_type ログの種類
	 * @param string $log_content ログの内容
	 */
	protected function writeLog($log_type, $log_content)
	{
		$data = [
			'log_type'    => $log_type,
			'log_content' => $log_content,
			'user_id'     => $this->readAuthUser('id'),
			'user_ip'     => $_SERVER['REMOTE_ADDR'],
			'user_agent'  => $_SERVER['HTTP_USER_AGENT']
		];
		
		
		$this->loadModel('Log');
		$this->Log->create();
		$this->Log->save($data);
	}
}
