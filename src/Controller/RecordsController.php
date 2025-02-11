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
 * Records Controller
 *
 * @property \App\Model\Table\RecordsTable $Records
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AppController
{

	/**
	 * 学習履歴を追加
	 * 
	 * @param int $content_id    コンテンツID
	 * @param int $is_complete   完了フラグ
	 * @param int $study_sec     学習時間
	 * @param int $understanding 理解度
	 */
	public function add($content_id, $is_complete, $study_sec, $understanding)
	{
		$this->autoRender = FALSE;
		
		// コンテンツ情報を取得
		$content = $this->fetchTable('Contents')->get($content_id, ['contain' => ['Courses']]);
		
		$data = [
			'user_id'		=> $this->readAuthUser('id'),
			'course_id'		=> $content->course->id,
			'content_id'	=> $content_id,
			'study_sec'		=> $study_sec,
			'understanding'	=> $understanding,
			'is_passed'		=> -1,
			'is_complete'	=> $is_complete
		];
		
		$record = $this->Records->newEmptyEntity();
		$record = $this->Records->patchEntity($record, $data);
		
		if($this->Records->save($record))
		{
			$this->Flash->success(__('学習履歴を保存しました'));

			return $this->redirect([
				'controller' => 'contents',
				'action' => 'index',
				$content->course->id
			]);
		}
	}
}
