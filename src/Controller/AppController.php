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
 * @since	  0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Routing\Router;
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

		$this->set('loginedUser', $this->readSession('Auth'));
		
		// 他のサイトの設定が存在する場合、設定情報及びログイン情報をクリア
		if($this->readSession('Setting'))
		{
			if($this->readSession('Setting.app_dir')!=APP_DIR)
			{
				// セッション内の設定情報を削除
				$this->deleteSession('Setting');
				
				// 他のサイトとのログイン情報の混合を避けるため、強制ログアウト
				if($this->Auth->user())
				{
					//$this->Cookie->delete('Auth');
					$this->redirect($this->Auth->logout());
					return;
				}
			}
		}

		// データベース内に格納された設定情報をセッションに格納
		if(!$this->readSession('Setting'))
		{
			$this->loadModel('Settings');
			$settings = $this->Settings->getSettings();
			
			$this->writeSession('Setting.app_dir', APP_DIR);
			
			foreach ($settings as $key => $value)
			{
				$this->writeSession('Setting.'.$key, $value);
			}
		}

		/*
		 * Enable the following component for recommended CakePHP form protection settings.
		 * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
		 */
		//$this->loadComponent('FormProtection');
	}

	protected function readSession($key)
	{
		return $this->getRequest()->getSession()->read($key);
	}

	protected function deleteSession($key)
	{
		return $this->getRequest()->getSession()->check($key);
	}

	protected function writeSession($key, $value)
	{
		return $this->getRequest()->getSession()->write($key, $value);
	}

	protected function readAuthUser($key)
	{
		return $this->getRequest()->getSession()->read('Auth.'.$key);
	}

	protected function getQuery($key)
	{
		$val = $this->getRequest()->getQuery($key);
		
		if($val=='')
			return null;
		
		return $val;
	}

	protected function getData()
	{
		$val = $this->getRequest()->getData();
		
		if($val=='')
			return null;
		
		return $val;
	}

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

	protected function writeLog($log_type, $log_content)
	{
		$data = array(
			'log_type'    => $log_type,
			'log_content' => $log_content,
			'user_id'     => $this->Auth->user('id'),
			'user_ip'     => $_SERVER['REMOTE_ADDR'],
			'user_agent'  => $_SERVER['HTTP_USER_AGENT']
		);
		
		
		$this->loadModel('Log');
		$this->Log->create();
		$this->Log->save($data);
	}
}
