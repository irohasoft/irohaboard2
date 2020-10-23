<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * ContentsQuestions Controller
 *
 * @property \App\Model\Table\ContentsQuestionsTable $ContentsQuestions
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsQuestionsController extends AdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Contents'],
        ];
        $contentsQuestions = $this->paginate($this->ContentsQuestions);

        $this->set(compact('contentsQuestions'));
    }

    /**
     * View method
     *
     * @param string|null $id Contents Question id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contentsQuestion = $this->ContentsQuestions->get($id, [
            'contain' => ['Contents'],
        ]);

        $this->set(compact('contentsQuestion'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contentsQuestion = $this->ContentsQuestions->newEmptyEntity();
        if ($this->request->is('post')) {
            $contentsQuestion = $this->ContentsQuestions->patchEntity($contentsQuestion, $this->request->getData());
            if ($this->ContentsQuestions->save($contentsQuestion)) {
                $this->Flash->success(__('The contents question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contents question could not be saved. Please, try again.'));
        }
        $contents = $this->ContentsQuestions->Contents->find('list', ['limit' => 200]);
        $this->set(compact('contentsQuestion', 'contents'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Contents Question id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contentsQuestion = $this->ContentsQuestions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contentsQuestion = $this->ContentsQuestions->patchEntity($contentsQuestion, $this->request->getData());
            if ($this->ContentsQuestions->save($contentsQuestion)) {
                $this->Flash->success(__('The contents question has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The contents question could not be saved. Please, try again.'));
        }
        $contents = $this->ContentsQuestions->Contents->find('list', ['limit' => 200]);
        $this->set(compact('contentsQuestion', 'contents'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Contents Question id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contentsQuestion = $this->ContentsQuestions->get($id);
        if ($this->ContentsQuestions->delete($contentsQuestion)) {
            $this->Flash->success(__('The contents question has been deleted.'));
        } else {
            $this->Flash->error(__('The contents question could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
