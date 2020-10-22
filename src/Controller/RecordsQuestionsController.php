<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * RecordsQuestions Controller
 *
 * @property \App\Model\Table\RecordsQuestionsTable $RecordsQuestions
 * @method \App\Model\Entity\RecordsQuestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsQuestionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Records', 'Questions'],
        ];
        $recordsQuestions = $this->paginate($this->RecordsQuestions);

        $this->set(compact('recordsQuestions'));
    }

    /**
     * View method
     *
     * @param string|null $id Records Question id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $recordsQuestion = $this->RecordsQuestions->get($id, [
            'contain' => ['Records', 'Questions'],
        ]);

        $this->set(compact('recordsQuestion'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $recordsQuestion = $this->RecordsQuestions->newEmptyEntity();
        if ($this->request->is('post')) {
            $recordsQuestion = $this->RecordsQuestions->patchEntity($recordsQuestion, $this->request->getData());
            if ($this->RecordsQuestions->save($recordsQuestion)) {
                $this->Flash->success(__('The records question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The records question could not be saved. Please, try again.'));
        }
        $records = $this->RecordsQuestions->Records->find('list', ['limit' => 200]);
        $questions = $this->RecordsQuestions->Questions->find('list', ['limit' => 200]);
        $this->set(compact('recordsQuestion', 'records', 'questions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Records Question id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $recordsQuestion = $this->RecordsQuestions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $recordsQuestion = $this->RecordsQuestions->patchEntity($recordsQuestion, $this->request->getData());
            if ($this->RecordsQuestions->save($recordsQuestion)) {
                $this->Flash->success(__('The records question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The records question could not be saved. Please, try again.'));
        }
        $records = $this->RecordsQuestions->Records->find('list', ['limit' => 200]);
        $questions = $this->RecordsQuestions->Questions->find('list', ['limit' => 200]);
        $this->set(compact('recordsQuestion', 'records', 'questions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Records Question id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $recordsQuestion = $this->RecordsQuestions->get($id);
        if ($this->RecordsQuestions->delete($recordsQuestion)) {
            $this->Flash->success(__('The records question has been deleted.'));
        } else {
            $this->Flash->error(__('The records question could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
