<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Routing\Router;

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
	    // 無限リダイレクトループの問題を防ぐことができます
	    $this->Authentication->addUnauthenticatedActions(['login']);
	}
	
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        //debug($result);
        // If the user is logged in send them away.
        if ($result->isValid()) {
            return $this->redirect(['controller' => 'UsersCourses', 'action' => 'index']);
        }

		// ユーザーが submit 後、認証失敗した場合は、エラーを表示します
        if ($this->request->is('post') && !$result->isValid()) {
			$this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
	    $result = $this->Authentication->getResult();
	    // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトします
	    if ($result->isValid()) {
	        $this->Authentication->logout();
	        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
	    }
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }
}
