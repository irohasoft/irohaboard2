<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * UsersCourses Controller
 *
 * @property \App\Model\Table\UsersCoursesTable $UsersCourses
 * @method \App\Model\Entity\UsersCourse[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersCoursesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
		$user_id = $this->readSession('Auth.id');

		// 全体のお知らせの取得
		$this->loadModel('Settings');
		$data = $this->Settings->find()->where(['setting_key' => 'information'])->toArray();;
		//debug($data);
		$info = $data[0]->setting_value;

		// お知らせ一覧を取得
		$this->loadModel('Infos');
		$infos = $this->Infos->getInfos($user_id, 2);
		//debug($infos);

		$no_info = "";

		// 全体のお知らせもお知らせも存在しない場合
		if(($info=="") && count($infos)==0)
			$no_info = __('お知らせはありません');

		// 受講コース情報の取得
		$courses = $this->UsersCourses->getCourseRecord($user_id);

		//debug($this->getRequest()->getSession()->read('Auth.id'));
		//debug($courses);

		$no_record = "";

		if(count($courses)==0)
			$no_record = __('受講可能なコースはありません');

		$this->set(compact('courses', 'no_record', 'info', 'infos', 'no_info'));
    }
}
