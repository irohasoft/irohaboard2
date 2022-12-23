<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 */

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;

/**
 * Infos Controller
 *
 * @property \App\Model\Table\InfosTable $Infos
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InfosController extends AppController
{
    /**
	 * お知らせ一覧を表示（受講者側）
     */
    public function index()
    {
		// お知らせ一覧を取得
		$this->fetchTable('Infos');
		$this->paginate = $this->Infos->getInfoOption($this->readAuthUser('id'));
		
		$infos = $this->paginate();
        
        $this->set(compact('infos'));
    }

    /**
	 * お知らせの内容を表示
	 * @param string $info_id 表示するお知らせのID
     */
	public function view($info_id)
    {
		if(!$this->Infos->exists(['id' => $info_id]))
		{
			throw new NotFoundException(__('Invalid info'));
		}

		// お知らせの閲覧権限の確認
		if(!$this->Infos->hasRight($this->readAuthUser('id'), $info_id))
		{
			throw new NotFoundException(__('Invalid access'));
		}
		
        $info = $this->Infos->get($info_id);
        
        $this->set(compact('info'));
    }

}
