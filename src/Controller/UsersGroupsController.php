<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * UsersGroups Controller
 *
 * @property \App\Model\Table\UsersGroupsTable $UsersGroups
 * @method \App\Model\Entity\UsersGroup[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersGroupsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Groups'],
        ];
        $usersGroups = $this->paginate($this->UsersGroups);

        $this->set(compact('usersGroups'));
    }

    /**
     * View method
     *
     * @param string|null $id Users Group id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $usersGroup = $this->UsersGroups->get($id, [
            'contain' => ['Users', 'Groups'],
        ]);

        $this->set(compact('usersGroup'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $usersGroup = $this->UsersGroups->newEmptyEntity();
        if ($this->request->is('post')) {
            $usersGroup = $this->UsersGroups->patchEntity($usersGroup, $this->request->getData());
            if ($this->UsersGroups->save($usersGroup)) {
                $this->Flash->success(__('The users group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users group could not be saved. Please, try again.'));
        }
        $users = $this->UsersGroups->Users->find('list', ['limit' => 200]);
        $groups = $this->UsersGroups->Groups->find('list', ['limit' => 200]);
        $this->set(compact('usersGroup', 'users', 'groups'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Users Group id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $usersGroup = $this->UsersGroups->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $usersGroup = $this->UsersGroups->patchEntity($usersGroup, $this->request->getData());
            if ($this->UsersGroups->save($usersGroup)) {
                $this->Flash->success(__('The users group has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The users group could not be saved. Please, try again.'));
        }
        $users = $this->UsersGroups->Users->find('list', ['limit' => 200]);
        $groups = $this->UsersGroups->Groups->find('list', ['limit' => 200]);
        $this->set(compact('usersGroup', 'users', 'groups'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Users Group id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $usersGroup = $this->UsersGroups->get($id);
        if ($this->UsersGroups->delete($usersGroup)) {
            $this->Flash->success(__('The users group has been deleted.'));
        } else {
            $this->Flash->error(__('The users group could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
