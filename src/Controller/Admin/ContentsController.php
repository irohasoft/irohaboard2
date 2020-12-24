<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Routing\Router;
use App\Vendor\Utils;

/**
 * Contents Controller
 *
 * @property \App\Model\Table\ContentsTable $Contents
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsController extends AdminController
{
	/**
	 * コンテンツ一覧の表示
	 *
	 * @param int $course_id コースID
	 */
	public function index($course_id)
	{
		$course_id = intval($course_id);
		
		// コースの情報を取得
		$course = $this->Contents->Courses->get($course_id);
		
		// コンテンツ一覧を取得
		$contents = $this->Contents->find('all')->where(['course_id' => $course_id])->order('Contents.sort_no');

		$this->set(compact('contents', 'course'));
	}

	/**
	 * コンテンツの追加
	 *
	 * @param int $course_id コースID
	 */
	public function add($course_id)
	{
		$this->edit($course_id);
		$this->render('edit');
	}

	/**
	 * コンテンツの編集
	 *
	 * @param int $course_id 所属するコースのID
	 * @param int $content_id 編集するコンテンツのID (指定しない場合、追加)
	 */
	public function edit($course_id, $content_id = null)
	{
		// コースの情報を取得
		$course = $this->Contents->Courses->get($course_id);
		
		if($this->action == 'add')
		{
			$content = $this->Contents->newEmptyEntity();
		}
		else
		{
			$content = $this->Contents->get($content_id, [
				'contain' => [],
			]);
		}
		
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$content = $this->Contents->patchEntity($content, $this->request->getData());
			
			// 新規追加の場合、コンテンツの作成者と所属コースを指定
			if($this->action == 'add')
			{
				$content->user_id	= $this->readAuthUser('id');
				$content->course_id = $course_id;
				$content->sort_no	= $this->Contents->getNextSortNo($course_id);
			}
			
			if ($this->Contents->save($content))
			{
				$this->Flash->success(__('The content has been saved.'));

				return $this->redirect(['action' => 'index', $course_id]);
			}
			
			$this->Flash->error(__('The content could not be saved. Please, try again.'));
		}
		
		$courses = $this->Contents->Courses->find('list');
		
		$this->set(compact('course', 'content', 'courses'));
	}

	/**
	 * コンテンツの削除
	 *
	 * @param int $content_id 削除するコンテンツのID
	 */
	public function delete($content_id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->request->allowMethod(['post', 'delete']);
		
		$content = $this->Contents->get($content_id);
		
		if ($this->Contents->delete($content))
		{
			// コンテンツに紐づくテスト問題も削除
			$this->LoadModel('ContentsQuestions');
			$this->ContentsQuestions->deleteAll(array('ContentsQuestions.content_id' => $content_id), false);
			$this->Flash->success(__('コンテンツが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The content could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index', $content->course_id]);
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
			//debug($this->getData('id_list'));
			$this->Contents->setOrder($this->getData('id_list'));
			echo "OK";
		}
	}
}
