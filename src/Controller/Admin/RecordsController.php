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

/**
 * Records Controller
 *
 * @property \App\Model\Table\RecordsTable $Records
 * @method \App\Model\Entity\Record[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class RecordsController extends AdminController
{

	public function initialize(): void
	{
		parent::initialize();
		
		// 検索処理のロードの追加
		$this->loadComponent('Search.Search', [
			'actions' => ['index'],	  // ここで検索するアクションを配列で指定
		]);
	}
	
	/**
	 * 学習履歴一覧を表示
	 */
	public function index()
	{
		$this->paginate = [
			'contain' => ['Courses', 'Users', 'Contents'],
			'order' => ['Records.created' => 'desc'],
		];
		
		$conditions = [];
		
		$conditions = $this->addCondition($conditions, 'course_id',		'Courses.id');
		$conditions = $this->addCondition($conditions, 'contenttitle',	'Contents.title LIKE');
		$conditions = $this->addCondition($conditions, 'username',		'Users.username LIKE');
		$conditions = $this->addCondition($conditions, 'name', 			'Users.name LIKE');
		
		$this->loadModel('Groups');
		$this->loadModel('Courses');
		
		// グループ
		if($this->getQuery('group_id'))
			$conditions['Users.id IN'] = $this->Groups->getUserIdByGroupID($this->getQuery('group_id'));
		
		// コンテンツ種別：学習の場合
		if($this->getQuery('content_category') == 'study')
			$conditions['Contents.kind IN'] = ['text', 'html', 'movie', 'url'];
		
		// コンテンツ種別：テストの場合
		if($this->getQuery('content_category') == 'test')
			$conditions['Contents.kind IN'] = ['test'];
		
		$from_date	= ($this->getQuery('from_date')) ? $this->getQuery('from_date') : date('Y-m-d', strtotime('-10 month'));
		$to_date	= ($this->getQuery('to_date')) ? $this->getQuery('to_date') : date('Y-m-d');
		
		$records = $this->paginate(
			$this->Records->find()
				->where($conditions)
				->where(['Records.created BETWEEN :from_date AND :to_date'])
				->bind(':from_date', $from_date, 'date')
				->bind(':to_date',	 $to_date.' 23:59:50', 'datetime')
		);
		
		$groups = $this->Groups->find('list');
		$courses = $this->Courses->find('list');
		
		$this->set(compact('records', 'groups', 'courses', 'from_date', 'to_date'));
	}
}
