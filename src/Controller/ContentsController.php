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
		$course = $this->fetchTable('Courses')->get($course_id);
		
		// ロールを取得
		$role = $this->readAuthUser('role');
		
		// 管理者かつ、学習履歴表示モードの場合、
		if($role != 'user' && $this->isAdminRecordPage())
		{
			$contents = $this->Contents->getContentRecord($user_id, $course_id, $role);
		}
		else
		{
			// コースの閲覧権限の確認
			if(!$this->fetchTable('Courses')->hasRight($this->readAuthUser('id'), $course_id))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			$contents = $this->Contents->getContentRecord($this->readAuthUser('id'), $course_id, $role);
		}
		
		// アップロードファイル参照用
		$this->writeCookie('LoginStatus', 'logined');
		$this->set(compact('course', 'contents'));
	}

	/**
	 * コンテンツの表示
	 * @param int $content_id 表示するコンテンツのID
	 */
	public function view($content_id)
	{
		$content_id = intval($content_id);
		
		if(!$this->Contents->exists(['id' => $content_id]))
		{
			throw new NotFoundException(__('Invalid content'));
		}

		// ヘッダー、フッターを非表示
		$this->viewBuilder()->disableAutoLayout();
		
		$content = $this->Contents->get($content_id, ['contain' => ['Courses'],]);

		// コンテンツの閲覧権限の確認
		if(!$this->fetchTable('Courses')->hasRight($this->readAuthUser('id'), $content->course_id))
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
		$this->set(compact('content'));
	}

	/**
	 * テスト結果を表示（管理者用）
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function adminRecord($course_id, $user_id)
	{
		if($this->readAuthUser('role') != 'admin')
			return;
		
		$this->index($course_id, $user_id);
		$this->render('index');
	}

	/**
	 * セッションに保存された情報を元にプレビュー
	 */
	public function preview()
	{
		// ヘッダー、フッターを非表示
		$this->viewBuilder()->disableAutoLayout();
		$this->set('content', $this->readSession('Iroha.preview_content'));
		$this->render('view');
	}
}
