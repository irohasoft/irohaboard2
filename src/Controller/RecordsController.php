<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Records Controller
 *
 * @property \App\Model\Table\RecordsTable $Records
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AppController
{
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
        $records = $this->paginate($this->Records);

        $this->set(compact('records'));
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
}
