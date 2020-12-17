<?php
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
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index($course_id)
    {
		$course_id = intval($course_id);
		
		// コースの情報を取得
		$this->loadModel('Courses');
		
		$course = $this->Courses->get($course_id);
		
		// ロールを取得
		$role = $this->readAuthUser('role');
		
		// 管理者かつ、学習履歴表示モードの場合、
		if($this->request->getParam('action') == 'admin_record')
		{
			$contents = $this->Contents->getContentRecord($user_id, $course_id, $role);
		}
		else
		{
			// コースの閲覧権限の確認
			if(! $this->Courses->hasRight($this->readAuthUser('id'), $course_id))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			$contents = $this->Contents->getContentRecord($this->readAuthUser('id'), $course_id, $role);
		}
		
		//debug($contents);
		/*
		exit;
		*/
		$this->set(compact('course', 'contents'));
    }

    /**
     * View method
     *
     * @param string|null $id Content id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $content = $this->Contents->get($id, [
            'contain' => ['Courses', 'Users', 'ContentsQuestions', 'Records'],
        ]);

        $this->set(compact('content'));
    }
}
