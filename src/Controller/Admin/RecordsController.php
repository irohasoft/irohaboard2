<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Records Controller
 *
 * @property \App\Model\Table\RecordsTable $Records
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AdminController
{
	public function initialize(): void
	{
		parent::initialize();
		
		// 検索処理のロードの追加
		$this->loadComponent('Search.Search', [
			'actions' => ['index'],	  // ここで検索するアクションを配列で指定
		]);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
		$this->paginate = [
			'contain' => ['Courses', 'Users', 'Contents'],
		];
		
		$conditions = [];
		
		$conditions = $this->addCondition($conditions, 'course_id',		'Courses.id');
		$conditions = $this->addCondition($conditions, 'contenttitle',	'Contents.title LIKE');
		$conditions = $this->addCondition($conditions, 'username',		'Users.username LIKE');
		$conditions = $this->addCondition($conditions, 'name', 			'Users.name LIKE');
		
		$this->loadModel('Groups');
		$this->loadModel('Courses');
		
		// グループ
		if($this->getQuery('group_id'))
			$conditions['Users.id IN'] = $this->Groups->getUserIdByGroupID($this->getQuery('group_id'));
		
		// コンテンツ種別：学習の場合
		if($this->getQuery('content_category')=='study')
			$conditions['Contents.kind IN'] = ['text', 'html', 'movie', 'url'];
		
		// コンテンツ種別：テストの場合
		if($this->getQuery('content_category')=='test')
			$conditions['Contents.kind IN'] = ['test'];
		
		$from_date	= ($this->getQuery('from_date')) ? $this->getQuery('from_date') : date('Y-m-d', strtotime('-10 month'));
		$to_date	= ($this->getQuery('to_date')) ? $this->getQuery('to_date') : date('Y-m-d');
		
		$records = $this->paginate(
			$this->Records->find('all')->where($conditions)
			->where(['Records.created BETWEEN :from_date AND :to_date'])
			->bind(':from_date', $from_date, 'date')
			->bind(':to_date',   $to_date, 'date')
		);
		
		$groups = $this->Groups->find('list');
		$courses = $this->Courses->find('list');
		
        $this->set(compact('records', 'groups', 'courses', 'from_date', 'to_date'));
    }

    /**
     * View method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => ['Courses', 'Users', 'Contents', 'RecordsQuestions'],
        ]);

        $this->set(compact('record'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $record = $this->Records->newEmptyEntity();
        if ($this->request->is('post')) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $this->Flash->success(__('The record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        }
        $courses = $this->Records->Courses->find('list', ['limit' => 200]);
        $users = $this->Records->Users->find('list', ['limit' => 200]);
        $contents = $this->Records->Contents->find('list', ['limit' => 200]);
        $this->set(compact('record', 'courses', 'users', 'contents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $record = $this->Records->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $record = $this->Records->patchEntity($record, $this->request->getData());
            if ($this->Records->save($record)) {
                $this->Flash->success(__('The record has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The record could not be saved. Please, try again.'));
        }
        $courses = $this->Records->Courses->find('list', ['limit' => 200]);
        $users = $this->Records->Users->find('list', ['limit' => 200]);
        $contents = $this->Records->Contents->find('list', ['limit' => 200]);
        $this->set(compact('record', 'courses', 'users', 'contents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Record id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $record = $this->Records->get($id);
        if ($this->Records->delete($record)) {
            $this->Flash->success(__('The record has been deleted.'));
        } else {
            $this->Flash->error(__('The record could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    private function getParam($target, $key)
    {
		if($target->data[$key])
			return $target->data[$key];
		debug($t->request);
		
		/*
		if($target->request->query[$key])
			return $target->request->query[$key];
		
		*/
		
		return null;
	}
}
