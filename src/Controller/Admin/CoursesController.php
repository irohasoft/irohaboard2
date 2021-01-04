<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Event\EventInterface;

/**
 * Courses Controller
 *
 * @property \App\Model\Table\CoursesTable $Courses
 * @method \App\Model\Entity\Course[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CoursesController extends AdminController
{
	public function initialize():void
	{
		parent::initialize();
		$this->loadComponent('Security');
	}

	public function beforeFilter(EventInterface $event)
	{
		parent::beforeFilter($event);
		$this->Security->setConfig('unlockedActions', ['order']);
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		$courses = $this->Courses->find('all')->order('Courses.sort_no');

		$this->set(compact('courses'));
	}

	/**
	 * コースの追加
	 */
	public function add()
	{
		$this->edit();
		$this->render('edit');
	}

	/**
	 * コースの編集
	 * @param int $course_id コースID
	 */
	public function edit($course_id = null)
	{
		if ($course_id) // 編集の場合
		{
			$course = $this->Courses->get($course_id, []);
		}
		else
		{
			$course = $this->Courses->newEmptyEntity();
		}

		if ($this->request->is(['patch', 'post', 'put']))
		{
			$course = $this->Courses->patchEntity($course, $this->request->getData());
			
			if ($this->Courses->save($course))
			{
				$this->Flash->success(__('コースが保存されました'));

				return $this->redirect(['action' => 'index']);
			}
			
			$this->Flash->error(__('The course could not be saved. Please, try again.'));
		}
		
		$this->set(compact('course'));
	}

	/**
	 * コースの削除
	 * @param int $course_id コースID
	 */
	public function delete($course_id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->request->allowMethod(['post', 'delete']);
		
		$course = $this->Courses->get($course_id);
		
		if ($this->Courses->delete($course))
		{
			$this->Flash->success(__('コースが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The course could not be deleted. Please, try again.'));
		}
		
		return $this->redirect(['action' => 'index']);
	}


	/**
	 * Ajax によるコースの並び替え
	 *
	 * @return string 実行結果
	 */
	public function order()
	{
		$this->autoRender = FALSE;
		
		if($this->request->is('ajax'))
		{
			debug($this->getData('id_list'));
			$this->Courses->setOrder($this->getData('id_list'));
			echo "OK";
		}
	}
}
