<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Infos Controller
 *
 * @property \App\Model\Table\InfosTable $Infos
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InfosController extends AdminController
{
	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$this->paginate = [
			'fields' => [
				'Infos.id',
				'Infos.title',
				'Infos.created',
				'Infos.modified',
				// 対象グループ一覧
				'group_title'	=> '(SELECT group_concat(g.title order by g.id SEPARATOR \', \') as group_title  FROM ib_infos_groups  ug INNER JOIN ib_groups  g ON g.id = ug.group_id  WHERE ug.info_id = Infos.id)',
			
			],
			'limit' => 20,
			'order' => [
				'Infos.created' => 'desc'
			],
			'contain' => ['Groups'],
		];
		
		$infos = $this->paginate();

		$this->set(compact('infos'));
	}

	/**
	 * Add method
	 *
	 * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
	 */
	public function add()
	{
		$this->edit();
		$this->render('edit');
	}

	/**
	 * お知らせの編集
	 * @param int $info_id 編集するお知らせのID
	 */
	public function edit($info_id = null)
	{
		if($this->action == 'edit' && !$this->Infos->exists($info_id))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		
		$info = $this->Infos->getOrNew($info_id, ['contain' => ['Groups']]);
		
		// 保存処理
		if($this->request->is(['patch', 'post', 'put']))
		{
			$info = $this->Infos->patchEntity($info, $this->getData());
			
			$info->info_id = $this->readAuthUser('id');
			
			if($this->Infos->save($info))
			{
				$this->Flash->success(__('お知らせが保存されました'));
				return $this->redirect(['action' => 'index']);
			}
			else
			{
				$this->Flash->error(__('The info could not be saved. Please, try again.'));
			}
		}
		
		$groups = $this->Infos->Groups->find('list');
		
		$this->set(compact('info', 'groups'));
	}

	/**
	 * お知らせを削除
	 * @param int $info_id 削除するお知らせのID
	 */
	public function delete($info_id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$info = $this->Infos->get($info_id);
		
		if($this->Infos->delete($info))
		{
			$this->Flash->success(__('お知らせが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The info could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(['action' => 'index']);
	}
}
