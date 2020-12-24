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
			'contain' => ['Users'],
		];
		$infos = $this->paginate($this->Infos);

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
		if ($this->action == 'edit' && !$this->Infos->exists(['id' => $info_id]))
		{
			throw new NotFoundException(__('Invalid info'));
		}
		
		// データの取得
		if($this->action == 'edit')
		{
			$info = $this->Infos->get($info_id, [
				'contain' => ['Groups'],
			]);
		}
		else
		{
			$info = $this->Infos->newEmptyEntity();
		}
		
		// 保存処理
		if ($this->request->is(['patch', 'post', 'put']))
		{
			$info = $this->Infos->patchEntity($info, $this->request->getData());
			
			$info->user_id = $this->getRequest()->getSession()->read('Auth.id');
			
			if ($this->Infos->save($info))
			{
				$this->Flash->success(__('The info has been saved.'));

				return $this->redirect(['action' => 'index']);
			}
			
			$this->Flash->error(__('The info could not be saved. Please, try again.'));
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
		
		if ($this->Infos->delete($info))
		{
			$this->Flash->success(__('The info has been deleted.'));
		}
		else
		{
			$this->Flash->error(__('The info could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(['action' => 'index']);
	}
}
