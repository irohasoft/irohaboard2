<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Infos Controller
 *
 * @property \App\Model\Table\InfosTable $Infos
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InfosController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        //debug($this->Infos->find()->where(['Infos.id' => 2])->first());
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $infos = $this->paginate($this->Infos);
        
        $this->set(compact('infos'));
    }

    /**
     * View method
     *
     * @param string|null $id Info id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($info_id = null)
    {
        $info = $this->Infos->get($info_id, [
            'contain' => ['Users', 'Groups'],
        ]);
        
        /*
        debug($info);
        debug($this->Infos->findById($info_id, [
            'contain' => ['Users', 'Groups'],
        ])->first());
        */
        $this->set(compact('info'));
    }
}
