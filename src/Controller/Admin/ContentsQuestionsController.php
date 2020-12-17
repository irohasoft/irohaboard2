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
    public function index($content_id)
    {
		$content_id = intval($content_id);
		
		// コンテンツ情報を取得
		$this->loadModel('Contents');
		$content = $this->Contents->get($content_id, [
			'contain' => ['Courses'],
		]);
		
		// 問題一覧を取得
        $contentsQuestions = $this->ContentsQuestions->find('all')->where(['content_id' => $content_id])->order('ContentsQuestions.sort_no');

        $this->set(compact('contentsQuestions', 'content'));
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


	/**
	 * Ajax によるコースの並び替え
	 *
	 * @return string 実行結果
	 */
	public function order()
	{
		$this->autoRender = FALSE;
		
		if($this->request->is('ajax'))
		{
			debug($this->getData('id_list'));
			$this->ContentsQuestions->setOrder($this->getData('id_list'));
			echo "OK";
		}
	}
}
