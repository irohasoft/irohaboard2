<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;

/**
 * Settings Controller
 *
 * @property \App\Model\Table\SettingsTable $Settings
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettingsController extends AdminController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			//debug($this->Settings->getSettings());
			$this->Settings->setSettings($this->getData());
			
			foreach ($this->request->getData() as $key => $value)
			{
				//debug($key.':'.$value);
				$this->writeSession('Setting.'.$key, $value);
			}
			
			$this->Flash->success(__('設定が保存されました'));
		}
		
		$this->set('settings',		$this->Settings->getSettings());
		$this->set('colors',		Configure::read('theme_colors'));
    }
}
