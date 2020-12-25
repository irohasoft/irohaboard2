<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AdminController
{
	public function initialize(): void
	{
		parent::initialize();
		
		// 検索処理のロードの追加
		$this->loadComponent('Search.Search', [
			'actions' => ['index'],	  // ここで検索するアクションを配列で指定
		]);
	}
	
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
		//debug($this->Users);
		// 選択中のグループをセッションから取得
		if($this->getQuery('mode'))
			$this->writeSession('Iroha.group_id', intval($this->getQuery('group_id')));
		
		// GETパラメータから検索条件を抽出
		$group_id	= ($this->getQuery('group_id')) ? $this->getQuery('group_id') : $this->readSession('Iroha.group_id');
		
		$conditions = [];
		/*
		debug($this->getRequest());
		debug($this->getRequest()->getQuery('group_id'));
		debug($this->readSession('Iroha.group_id'));
		debug($this->getQuery('group_id'));
		debug(intval($this->getQuery('group_id')));
		debug($group_id);
		*/
		// 独自の検索条件を追加（指定したグループに所属するユーザを検索）
		if($group_id)
			$conditions['Users.id IN'] = $this->Users->Groups->getUserIdByGroupID($group_id);
		
		if($this->getQuery('username'))
			$conditions['Users.username LIKE'] = '%'.$this->getQuery('username').'%';
		
		if($this->getQuery('name'))
			$conditions['Users.name LIKE'] = '%'.$this->getQuery('name').'%';
		
		//debug($conditions);
		
		$this->paginate = [
			'fields' => [
				'Users.id',
				'Users.username',
				'Users.name',
				'Users.role',
				'Users.last_logined',
				'Users.created',
				// 所属グループ一覧
				'group_title'	=> '(SELECT group_concat(g.title order by g.id SEPARATOR \', \') as group_title  FROM ib_users_groups  ug INNER JOIN ib_groups  g ON g.id = ug.group_id  WHERE ug.user_id = Users.id)',
				// 受講コース一覧
				'course_title'	=> '(SELECT group_concat(c.title order by c.id SEPARATOR \', \') as course_title FROM ib_users_courses uc INNER JOIN ib_courses c ON c.id = uc.course_id WHERE uc.user_id = Users.id)',
			
			],
			'limit' => 20,
			'order' => [
				'Users.created' => 'desc'
			]
		];
		$users = $this->paginate($this->Users->find('all')->where($conditions));
		
		//debug($users);
		// グループ一覧を取得
		$groups = $this->Users->Groups->find('list');
		
		$this->set(compact('users', 'groups', 'group_id'));
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
		if ($this->action == 'edit' && !$this->Users->exists(['id' => $user_id]))
		{
			throw new NotFoundException(__('Invalid user'));
		}
		
		// データの取得
		if($user_id)
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
			
			if ($this->request->getData('new_password') !== '')
				$user->password = $this->request->getData('new_password');
			
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

	/**
	 * パスワード変更
	 */
	public function setting()
	{
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
