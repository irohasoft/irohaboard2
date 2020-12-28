<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Routing\Router;
/*
use Cake\Http\Cookie\Cookie;
use Cake\Http\Cookie\CookieCollection;
use Cake\Http\Middleware\EncryptedCookieMiddleware;
use DateTime;
*/

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// custom 2020.10.6
		// ログインアクションを認証を必要としないように設定することで、
		// 無限リダイレクトループの問題を防止
		$this->Authentication->addUnauthenticatedActions(['login']);
	}
	
	public function login()
	{
		//debug($this->request->getCookie('Auth'));
		/*
		$this->request = $this->request->withData('username', $this->request->getCookie('Auth.username'));
		$this->request = $this->request->withData('password', $this->request->getCookie('Auth.password'));
		$this->request = $this->request->withData('remember_me', $this->request->getCookie('Auth.remember_me'));
		//debug($this->getData());
		//exit;
		*/
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		//debug($result);
		// If the user is logged in send them away.
		if ($result->isValid())
		{
			/*
			if($this->getData('remember_me'))
			{
				$cookie = $this->getData();
				$this->response = $this->response->withCookie(Cookie::create(
					'Auth',
					$cookie,
					[
						'expires' => new DateTime('+2 weeks'),
//						'secure' => true,
//						'http' => true,
					]
				));
			}
			*/
			return $this->redirect(['controller' => 'UsersCourses', 'action' => 'index']);
		}

		// ユーザーが submit 後、認証失敗した場合は、エラーを表示
		if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('ログインID、もしくはパスワードが正しくありません'));
		}
	}

	public function logout()
	{
		$result = $this->Authentication->getResult();
		// POSTやGETに関係なく、ユーザーがログインしていればリダイレクト
		if ($result->isValid())
		{
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}
	
	/**
	 * パスワード変更
	 */
	public function setting()
	{
		/*
		$data = $this->request->getCookie('Auth');
		debug($data);
		*/
		if ($this->request->is(array(
				'post',
				'put'
		)))
		{
			if(Configure::read('demo_mode'))
				return;
			$data = $this->getData();
			
			//debug($data);
			
			if($data['User']['new_password'] != $data['User']['new_password2'])
			{
				$this->Flash->error(__('入力された「パスワード」と「パスワード（確認用）」が一致しません'));
				return;
			}
			
			if($data['User']['new_password'] !== '')
			{
				$user = $this->Users->get($this->readAuthUser('id'));
				
				$user->password = $data['User']['new_password'];
				
				if ($this->Users->save($user))
				{
					$this->Flash->success(__('パスワードが保存されました'));
					$data = null;
				}
				else
				{
					$this->Flash->error(__('The user could not be saved. Please, try again.'));
				}
			}
			else
			{
				$this->Flash->error(__('パスワードを入力して下さい'));
			}
		}
	}
}
