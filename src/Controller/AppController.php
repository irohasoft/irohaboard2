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

	/**
	 * クッキーの取得
	 * @param string $key キー
	 */
	protected function readCookie($key)
	{
		return $this->getRequest()->getCookie($key);
	}

	/**
	 * クッキーの削除
	 * @param string $key キー
	 */
	protected function deleteCookie($key)
	{
		/*
		暗号化されたクッキーは このメソッドでは削除できない
		*/
		$cookie = new Cookie($key);
		$this->response = $this->response->withExpiredCookie($cookie);
	}

	/**
	 * クッキーの存在確認
	 * @param string $key キー
	 */
	protected function hasCookie($key)
	{
		return ($this->readCookie($key) != null);
	}

	/**
	 * クッキーの保存
	 * @param string $key キー
	 * @param string $value 値
	 */
	protected function writeCookie($key, $value, $encrypt = true, $expires = '+2 weeks')
	{
		$cookie = (new Cookie($key, $value))
			->withExpiry(new DateTime($expires))
			->withHttpOnly(true);
		/*
		クッキーの暗号化は Middleware にて行う
		*/
		$this->response = $this->response->withCookie($cookie);
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
