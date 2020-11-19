<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AdminController
{
	// custom 2020.06.07
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// custom 2020.10.6
		// ログインアクションを認証を必要としないように設定することで、
		// 無限リダイレクトループの問題を防ぐことができます
		$this->Authentication->addUnauthenticatedActions(['login']);
	}

	// custom 2020.06.07
	public function login()
	{
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		
		// If the user is logged in send them away.
		if ($result->isValid()) {
			return $this->redirect(['controller' => 'Users', 'action' => 'index']);
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

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$this->edit();
		$this->render('edit');
	}

	/**
	 * Edit method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function edit($user_id = null)
	{
		if ($this->request->getParam('action') == 'edit' && !$this->Users->exists(['id' => $user_id]))
		{
			throw new NotFoundException(__('Invalid user'));
		}
		
		// データの取得
		if($this->request->getParam('action') == 'edit')
		{
			$user = $this->Users->get($user_id, [
				'contain' => ['Courses', 'Groups'],
			]);
		}
		else
		{
			$user = $this->Users->newEmptyEntity();
		}
		
		// 保存処理
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$user = $this->Users->patchEntity($user, $this->request->getData());
			
			$user->user_id = $this->getRequest()->getSession()->read('Auth.id');
			
			if ($this->Users->save($user))
			{
				$this->Flash->success(__('The user has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			
			$this->Flash->error(__('The user could not be saved. Please, try again.'));
		}
		
		$courses = $this->Users->Courses->find('list');
		$groups = $this->Users->Groups->find('list');
		
		$this->set(compact('user', 'courses', 'groups'));
	}

	/**
	 * Delete method
	 *
	 * @param string|null $id User id.
	 * @return \Cake\Http\Response|null|void Redirects to index.
	 * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($id);
		if ($this->Users->delete($user)) {
			$this->Flash->success(__('The user has been deleted.'));
		} else {
			$this->Flash->error(__('The user could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
