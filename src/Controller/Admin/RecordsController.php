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
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
		$group_id			= (isset($this->request->query['group_id'])) ? $this->request->query['group_id'] : "";
		$course_id			= (isset($this->request->query['course_id'])) ? $this->request->query['course_id'] : "";
		$username			= (isset($this->request->query['username'])) ? $this->request->query['username'] : "";
		$name				= (isset($this->request->query['name'])) ? $this->request->query['name'] : "";
		$content_category	= (isset($this->request->query['content_category'])) ? $this->request->query['content_category'] : "";
		$contenttitle		= (isset($this->request->query['contenttitle'])) ? $this->request->query['contenttitle'] : "";
		
		$from_date	= (isset($this->request->query['from_date'])) ? 
			$this->request->query['from_date'] : 
				array(
					'year' => date('Y', strtotime("-1 month")),
					'month' => date('m', strtotime("-1 month")), 
					'day' => date('d', strtotime("-1 month"))
				);
		
		$to_date	= (isset($this->request->query['to_date'])) ? 
			$this->request->query['to_date'] : 
				array('year' => date('Y'), 'month' => date('m'), 'day' => date('d'));
		
		$this->paginate = [
			'contain' => ['Courses', 'Users', 'Contents'],
		];
		
		$records = $this->paginate($this->Records->find('all'));

		$this->loadModel('Groups');
		$this->loadModel('Courses');
		$groups = $this->Groups->find('list');
		$courses = $this->Courses->find('list');
		
        $this->set(compact('records', 'groups', 'courses', 'group_id', 'course_id', 'username', 'name', 'content_category', 'contenttitle', 'from_date', 'to_date'));
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
}
