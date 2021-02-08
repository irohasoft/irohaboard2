<?php
/**
 * iroha Board Project
 *
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 * @link          https://irohaboard.irohasoft.jp
 * @license       https://www.gnu.org/licenses/gpl-3.0.en.html GPL License
 */

declare(strict_types=1);

namespace App\Controller\Admin;
use Cake\Datasource\ConnectionManager;

/**
 * ContentsQuestions Controller
 *
 * @property \App\Model\Table\ContentsQuestionsTable $ContentsQuestions
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsQuestionsController extends AdminController
{
	/**
	 * 問題一覧を表示
	 * @param int $content_id 表示するコンテンツ(テスト)のID
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
		$contentsQuestions = $this->ContentsQuestions->find()
			->where(['content_id' => $content_id])
			->order('ContentsQuestions.sort_no')
			->all();
		
		$this->set(compact('contentsQuestions', 'content'));
	}

	/**
	 * 問題を追加
	 * @param int $content_id 追加対象のコンテンツ(テスト)のID
	 */
	public function add($content_id)
	{
		$this->edit($content_id);
		$this->render('edit');
	}

	/**
	 * 問題を編集
	 * @param int $content_id 追加対象のコンテンツ(テスト)のID
	 * @param int $question_id 編集対象の問題のID
	 */
	public function edit($content_id, $question_id = null)
	{
		// コンテンツ情報を取得
		$content = $this->ContentsQuestions->Contents->get($content_id, [
			'contain' => ['Courses'],
		]);

		$contentsQuestion = $this->ContentsQuestions->getOrNew($question_id, ['contain' => []]);
		
		if($this->request->is(['patch', 'post', 'put']))
		{
			$contentsQuestion = $this->ContentsQuestions->patchEntity($contentsQuestion, $this->getData());
			
			$conn = ConnectionManager::get('default');
			$conn->getDriver()->enableAutoQuoting();
			
			// 新規追加の場合、コンテンツの作成者と所属コースを指定
			if($this->action == 'add')
			{
				$contentsQuestion->user_id	= $this->readAuthUser('id');
				$contentsQuestion->content_id = $content_id;
				$contentsQuestion->sort_no	= $this->ContentsQuestions->getNextSortNo($content_id);
			}
			
			if($this->ContentsQuestions->save($contentsQuestion)) {
				$this->Flash->success(__('問題が保存されました'));

				return $this->redirect(['action' => 'index', $content_id]);
			}
			
			$this->Flash->error(__('The contents question could not be saved. Please, try again.'));
		}
		
		$this->set(compact('content', 'contentsQuestion'));
	}

	/**
	 * 問題を削除
	 * @param int $question_id 削除対象の問題のID
	 */
	public function delete($question_id)
	{
		$this->request->allowMethod(['post', 'delete']);
		$contentsQuestion = $this->ContentsQuestions->get($question_id);
		
		if($this->ContentsQuestions->delete($contentsQuestion))
		{
			$this->Flash->success(__('問題が削除されました'));
		}
		else
		{
			$this->Flash->error(__('The contents question could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(['action' => 'index', $contentsQuestion->content_id]);
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
