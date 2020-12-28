<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Contents Controller
 *
 * @property \App\Model\Table\ContentsTable $Contents
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsController extends AppController
{
	/**
	 * 学習コンテンツ一覧を表示
	 * @param int $course_id コースID
	 * @param int $user_id 学習履歴を表示するユーザのID
	 */
	public function index($course_id, $user_id = null)
	{
		$course_id = intval($course_id);
		
		// コースの情報を取得
		$this->loadModel('Courses');
		
		$course = $this->Courses->get($course_id);
		
		// ロールを取得
		$role = $this->readAuthUser('role');
		
		$is_admin_record = (($role=='admin') && ($this->action=='adminRecord'));
		
		// 管理者かつ、学習履歴表示モードの場合、
		if($is_admin_record)
		{
			$contents = $this->Contents->getContentRecord($user_id, $course_id, $role);
		}
		else
		{
			//debug($this->readAuthUser('id'));
			// コースの閲覧権限の確認
			if(! $this->Courses->hasRight($this->readAuthUser('id'), $course_id))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			$contents = $this->Contents->getContentRecord($this->readAuthUser('id'), $course_id, $role);
		}
		
		//debug($contents);
		/*
		exit;
		*/
		$this->set(compact('course', 'contents', 'is_admin_record'));
	}

	/**
	 * テスト結果を表示（管理者用）
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function adminRecord($course_id, $user_id)
	{
		if($this->readAuthUser('role')!='admin')
			return;
		
		$this->index($course_id, $user_id);
		$this->render('index');
	}

	/**
	 * コンテンツの表示
	 * @param int $content_id 表示するコンテンツのID
	 */
	public function view($content_id = null)
	{
		// ヘッダー、フッターを非表示
		$this->viewBuilder()->disableAutoLayout();
		
		$content = $this->Contents->get($content_id, [
			'contain' => ['Courses'],
		]);

		$this->set(compact('content'));
	}
}
