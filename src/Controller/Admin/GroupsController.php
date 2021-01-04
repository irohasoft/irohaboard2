<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 * @method \App\Model\Entity\Group[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class GroupsController extends AdminController
{
	/**
	 * グループ一覧を表示
	 */
	public function index()
	{
		$this->paginate = [
			'fields' => [
				'Groups.id',
				'Groups.title',
				'Groups.created',
				'Groups.modified',
				// 受講コース一覧
				'course_title'	=> '(SELECT group_concat(c.title order by c.id SEPARATOR \', \') as course_title FROM ib_groups_courses uc INNER JOIN ib_courses c ON c.id = uc.course_id WHERE uc.group_id = Groups.id)',
			
			],
			'limit' => 20,
			'order' => [
				'Groups.created' => 'desc'
			],
			'contain' => ['Courses'],
		];
		
		$groups = $this->paginate($this->Groups);
		$this->set(compact('groups'));
	}

	/**
	 * グループの追加
	 */
	public function add()
	{
		$this->edit();
		$this->render('edit');
	}

	/**
	 * グループの編集
	 * @param int $group_id 編集するグループのID
	 */
	public function edit($group_id = null)
	{
		if ($group_id) // 編集の場合
		{
			$group = $this->Groups->get($group_id, [
				'contain' => ['Courses'],
			]);
		}
		else
		{
			$group = $this->Groups->newEmptyEntity();
		}
		
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$group = $this->Groups->patchEntity($group, $this->request->getData());
			
			if ($this->Groups->save($group))
			{
				$this->Flash->success(__('グループ情報を保存しました'));

				return $this->redirect(['action' => 'index']);
			}
			
			$this->Flash->error(__('The group could not be saved. Please, try again.'));
		}
		
		$courses = $this->Groups->Courses->find('list');
		$this->set(compact('group', 'courses'));
	}

	/**
	 * グループの削除
	 * @param int $group_id 削除するグループのID
	 */
	public function delete($id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$group = $this->Groups->get($id);
		if ($this->Groups->delete($group)) {
			$this->Flash->success(__('The group has been deleted.'));
		} else {
			$this->Flash->error(__('The group could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index']);
	}
}
