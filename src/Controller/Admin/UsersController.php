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

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

use App\Vendor\Utils;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AdminController
{
	public function initialize(): void
	{
		parent::initialize();
		
		// 検索処理のロードの追加
		$this->loadComponent('Search.Search', [
			'actions' => ['index'],	  // ここで検索するアクションを配列で指定
		]);
	}
	
	// custom 2020.06.07
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		parent::beforeFilter($event);
		// custom 2020.10.6
		// ログインアクションを認証を必要としないように設定することで、
		// 無限リダイレクトループの問題を防ぐことができます
		$this->Authentication->addUnauthenticatedActions(['login']);
	}

	/**
	 * Index method
	 *
	 * @return \Cake\Http\Response|null|void Renders view
	 */
	public function index()
	{
		//debug($this->Users);
		// 選択中のグループをセッションから取得
		if($this->getQuery('mode'))
			$this->writeSession('Iroha.group_id', intval($this->getQuery('group_id')));
		
		// GETパラメータから検索条件を抽出
		$group_id = ($this->getQuery('group_id')) ? $this->getQuery('group_id') : $this->readSession('Iroha.group_id');
		//debug($group_id);
		
		$conditions = [];
		
		// 独自の検索条件を追加（指定したグループに所属するユーザを検索）
		if($group_id)
			$conditions['Users.id IN'] = $this->Users->Groups->getUserIdByGroupID($group_id);
		
		if($this->getQuery('username'))
			$conditions['Users.username LIKE'] = '%'.$this->getQuery('username').'%';
		
		if($this->getQuery('name'))
			$conditions['Users.name LIKE'] = '%'.$this->getQuery('name').'%';
		
		//debug($conditions);
		
		$this->paginate = [
			'fields' => [
				'Users.id',
				'Users.username',
				'Users.name',
				'Users.role',
				'Users.last_logined',
				'Users.created',
				// 所属グループ一覧
				'group_title'	=> '(SELECT group_concat(g.title order by g.id SEPARATOR \', \') as group_title  FROM ib_users_groups  ug INNER JOIN ib_groups  g ON g.id = ug.group_id  WHERE ug.user_id = Users.id)',
				// 受講コース一覧
				'course_title'	=> '(SELECT group_concat(c.title order by c.id SEPARATOR \', \') as course_title FROM ib_users_courses uc INNER JOIN ib_courses c ON c.id = uc.course_id WHERE uc.user_id = Users.id)',
			
			],
			'conditions' => $conditions,
			'limit' => 20,
			'order' => [
				'Users.created' => 'desc'
			]
		];
		
		$users = $this->paginate();

		// CSV出力モードの場合
		if($this->getQuery('cmd') == 'export')
		{
			$this->export($conditions);
		}
		
		//debug($users);
		// グループ一覧を取得
		$groups = $this->Users->Groups->find('list');
		
		$this->set(compact('users', 'groups', 'group_id'));
	}

	/**
	 * ユーザを追加（編集画面へ）
	 */
	public function add()
	{
		$this->edit();
		$this->render('edit');
	}

	/**
	 * ユーザ情報編集
	 * @param int $user_id 編集対象のユーザのID
	 */
	public function edit($user_id = null)
	{
		if($this->isEditPage() && !$this->Users->exists(['id' => $user_id]))
		{
			throw new NotFoundException(__('Invalid user'));
		}
		
		$username = '';
		
		$user = $this->Users->getOrNew($user_id, ['contain' => ['Courses', 'Groups'],]);
		
		// 保存処理
		if($this->request->is(['patch', 'post', 'put']))
		{
			$user->user_id = $this->readAuthUser('id');
			
			if($this->request->getData('new_password') !== '')
				$user->password = $this->request->getData('new_password');
			
			$user = $this->Users->patchEntity($user, $this->getData());
			
			if($this->Users->save($user))
			{
				$this->Flash->success(__('ユーザ情報が保存されました'));

				return $this->redirect(['action' => 'index']);
			}
			
			$this->Flash->error(__('ユーザ情報が保存できませんでした'));
		}
		
		if($user)
			$username = $user->username;
		
		$courses = $this->Users->Courses->find('list');
		$groups = $this->Users->Groups->find('list');
		
		$this->set(compact('user', 'courses', 'groups', 'username'));
	}

	/**
	 * ユーザの削除
	 *
	 * @param int $user_id 削除するユーザのID
	 */
	public function delete($user_id = null)
	{
		$this->request->allowMethod(['post', 'delete']);
		$user = $this->Users->get($user_id);
		
		if($this->Users->delete($user))
		{
			$this->Flash->success(__('ユーザが削除されました'));
		}
		else
		{
			$this->Flash->error(__('ユーザを削除できませんでした'));
		}
		
		return $this->redirect(['action' => 'index']);
	}

	/**
	 * ユーザの学習履歴のクリア
	 *
	 * @param int $user_id 学習履歴をクリアするユーザのID
	 */
	public function clear($user_id)
	{
		$this->request->allowMethod('post', 'delete');
		$this->Users->deleteUserRecords($user_id);
		$this->Flash->success(__('学習履歴を削除しました'));
		return $this->redirect(['action' => 'edit', $user_id]);
	}

	/**
	 * パスワード変更
	 */
	public function setting()
	{
		$user = $this->Users->get($this->readAuthUser('id'));
		$this->set(compact('user'));
		
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$data = $this->getData();
			
			if($data['new_password'] == '')
			{
				$this->Flash->error(__('パスワードを入力して下さい'));
				return;
			}
			
			if($data['new_password'] != $data['new_password2'])
			{
				$this->Flash->error(__('入力された「パスワード」と「パスワード（確認用）」が一致しません'));
				return;
			}
			
			if($data['new_password'] !== '')
			{
				$user->password = $data['new_password'];
				$user = $this->Users->patchEntity($user, $data);
				
				if($this->Users->save($user))
				{
					$this->Flash->success(__('パスワードが保存されました'));
					$data = null;
				}
				else
				{
					$this->Flash->error(__('パスワードが保存できませんでした'));
				}
			}
		}
	}

	/**
	 * ログイン
	 */
	public function login()
	{
		$this->request->allowMethod(['get', 'post']);
		$result = $this->Authentication->getResult();
		//debug($result);
		
		// If the user is logged in send them away.
		if($result->isValid())
		{
			return $this->redirect(['controller' => 'Users', 'action' => 'index']);
		}

		// ユーザーが submit 後、認証失敗した場合は、エラーを表示します
		if($this->request->is('post') && !$result->isValid())
		{
			$this->Flash->error(__('ログインID、もしくはパスワードが正しくありません'));
		}
	}

	/**
	 * ログアウト
	 */
	public function logout()
	{
		$result = $this->Authentication->getResult();
		
		// POSTやGETに関係なく、ユーザーがログインしていればリダイレクトします
		if($result->isValid()) {
			$this->Authentication->logout();
			return $this->redirect(['controller' => 'Users', 'action' => 'login']);
		}
	}

	/**
	 * ユーザ情報のインポート
	 */
	public function import()
	{
		if(Configure::read('demo_mode'))
			return;
		
		$group_count  = Configure::read('import_group_count');		// 所属グループの列数
		$course_count = Configure::read('import_course_count');		// 受講コースの列数
		
		//------------------------------//
		//	列番号の定義				//
		//------------------------------//
		define('COL_LOGINID',	0);
		define('COL_PASSWORD',	1);
		define('COL_NAME',		2);
		define('COL_ROLE',		3);
		define('COL_EMAIL',		4);
		define('COL_COMMENT',	5);
		define('COL_GROUP',		6);
		define('COL_COURSE',	6 + $group_count);
		
		$err_msg = '';
		
		if($this->request->is(['post', 'put']))
		{
			//------------------------------//
			//	CSVファイルの読み込み		//
			//------------------------------//
			// 制限時間を120秒に設定
			set_time_limit(120);
			
			//$csvfile = $this->getData('csvfile');
			
			$files = $this->request->getUploadedFiles();
			//debug($files['csvfile']->getStream()->getMetadata('uri'));
			
			// インポートファイルが指定されていない場合、エラーメッセージを表示
			if($files['csvfile']->getError() != 0)
			{
				$this->Flash->error(__('インポートファイルが指定されていません'));
				$this->set(compact('err_msg'));
				return;
			}
			
			// CSVファイルの読み込み
			$path = $files['csvfile']->getStream()->getMetadata('uri');
			//debug($path);
			$csv = Utils::getCsvData($path);
			
			$i = 0;
			
			$ds = ConnectionManager::get('default');
			$ds->begin();
			
			try
			{
				$is_error = false;
				
				$group_list  = $this->Users->Groups->find('list');	// 所属グループ
				$course_list = $this->Users->Courses->find('list');	// 受講コース
				
				// 1行ごとにデータを登録
				foreach($csv as $row)
				{
					$i++;
					
					if($i < 2)
						continue;
					
					if(count($row) < 5)
						continue;
					
					$is_new = false;
					
					//------------------------------//
					//	ユーザ情報の作成			//
					//------------------------------//
					$data = $this->Users->find()
						->where(['Users.username' => $row[COL_LOGINID]])
						->contain(['Courses', 'Groups'])
						->first();
					
					// 指定したログインIDのユーザが存在しない場合、新規追加とする
					if(!$data)
					{
						$data = $this->Users->newEmptyEntity();
						$data->created = date('Y-m-d H:i:s');
						$is_new = true;
					}
					
					// ユーザ名
					$data->username = $row[COL_LOGINID];
					
					// パスワード
					if($row[COL_PASSWORD] == '')
					{
						unset($data->password);
					}
					else
					{
						$data->password = $row[COL_PASSWORD];
					}
					
					$data->name = $row[COL_NAME];											// 氏名
					$data->role = Utils::getKeyByValue('user_role', $row[COL_ROLE]);		// 権限
					$data->email = $row[COL_EMAIL];											// メールアドレス
					$data->comment = Utils::issetOr($row[COL_COMMENT]);						// 備考
					//debug($data->groups);
					//----------------------------------//
					//	所属グループ・受講コースの割当	//
					//----------------------------------//
					$data->groups = [];		// 所属グループの割当の初期化
					$data->courses = [];	// 受講コースの割当の初期化
					
					// 所属グループの割当
					for($n=0; $n < $group_count; $n++)
					{
						$title = Utils::issetOr($row[COL_GROUP + $n], '');
						
						if($title == '')
							continue;
						
						$group_id = Utils::getIdByTitle($group_list, $title);
						
						if($group_id == null)
							continue;
						
						$group = $this->Users->Groups->get($group_id);
						$data->groups[] = $group;
					}
					
					// 受講コースの割当
					for($n=0; $n < $course_count; $n++)
					{
						$title = Utils::issetOr($row[COL_COURSE + $n], '');
						
						if($title == '')
							continue;
						
						$course_id = Utils::getIdByTitle($course_list, $title);
						
						if($course_id == null)
							continue;
						
						$course = $this->Users->Courses->get($course_id);
						$data->courses[] = $course;
					}
					
					$data->modified = date('Y-m-d H:i:s');
					
					//debug($data);
					
					//------------------------------//
					//	保存						//
					//------------------------------//
					if(!$this->Users->save($data))
					{
						//debug($data);
						//debug($this->Users->validationErrors);
						
						// 保存時にエラーが発生した場合、モデルからエラー情報を抽出
						$err_list = $data->getErrors();
						
						//debug($err_list);
						foreach($err_list as $key => $value)
						{
							foreach($err_list[$key] as $key2 => $value2)
							{
								$err_msg .= '<li>'.$i.'行目 : '.$value2.'</li>';
							}
						}
						
						$is_error = true;
					}
				}
				
				//------------------------------//
				//	エラー処理					//
				//------------------------------//
				if($is_error)
				{
					$ds->rollback();
					$this->Flash->error(__('インポートに失敗しました'));
				}
				else
				{
					$ds->commit();
					$this->Flash->success(__('インポートが完了しました'));
					return $this->redirect([
						'action' => 'index'
					]);
				}
			}
			catch (Exception $e)
			{
				$ds->rollback();
				$this->Flash->error(__('インポートに失敗しました'));
			}
		}
		
		$this->set(compact('err_msg'));
	}

	/**
	 * ユーザ情報のエクスポート
	 */
	public function export($conditions)
	{
		$group_count  = Configure::read('import_group_count');		// 所属グループの列数
		$course_count = Configure::read('import_course_count');		// 受講コースの列数
		
		$this->autoRender = false;
		Configure::write('debug', 0);

		//Content-Typeを指定
		$this->response->withType('csv');
		
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="users_'.date('Ymd').'.csv"');
		
		$fp = fopen('php://output','w');
		
		//------------------------------//
		//	ヘッダー行の作成			//
		//------------------------------//
		$header = [
			__('ログインID'),
			__('パスワード'),
			__('氏名'),
			__('権限'),
			__('メールアドレス'),
			__('備考'),
		];
		
		for($n=0; $n < $group_count; $n++)
		{
			$header[count($header)] = __('グループ').($n+1);
		}
		
		for($n=0; $n < $course_count; $n++)
		{
			$header[count($header)] = __('コース').($n+1);
		}
		
		// ヘッダー行をCSV出力
		mb_convert_variables('SJIS-win', 'UTF-8', $header);
		fputcsv($fp, $header);
		
		//------------------------------//
		//	ユーザ情報の取得			//
		//------------------------------//
		
		// パフォーマンスの改善の為、一定件数に分割してデータを取得
		$limit      = 500;
		$user_count = $this->Users->find()->where($conditions)->count();	// ユーザ数を取得
		$page_size  = ceil($user_count / $limit);	// ページ数（ユーザ数 / ページ単位）
		
		// ページ単位でユーザを取得
		for($page=1; $page <= $page_size; $page++)
		{
			// ユーザ情報を取得
			$rows = $this->Users->find('all')
				->where($conditions)
				->limit($limit)
				->page($page)
				->contain(['Groups', 'Courses']);
			
			foreach($rows as $row)
			{
				//------------------------------//
				//	出力するデータを作成		//
				//------------------------------//
				$groups  = [];
				$courses = [];
				
				for($n=0; $n < $group_count; $n++)
					$groups[] = '';
				
				for($n=0; $n < $course_count; $n++)
					$courses[] = '';
				
				$i = 0;
				
				// 所属グループのリストを作成
				foreach($row->groups as $group)
				{
					$groups[$i] = $group->title;
					$i++;
				}
				
				$i = 0;
				
				// 受講コースのリストを作成
				foreach($row->courses as $course)
				{
					$courses[$i] = $course->title;
					$i++;
				}
				
				// 出力行を作成
				$line = [
					$row->username,								// ユーザ名
					'',											// パスワード
					$row->name,									// 氏名
					Configure::read('user_role.'.$row->role),	// 権限
					$row->email,								// メールアドレス
					$row->comment,								// 備考
				];
				
				// 所属グループを出力
				for($n=0; $n < $group_count; $n++)
				{
					$line[count($line)] = $groups[$n];
				}
				
				// 受講コースを出力
				for($n=0; $n < $course_count; $n++)
				{
					$line[count($line)] = $courses[$n];
				}
				
				
				// CSV出力
				mb_convert_variables('SJIS-win', 'UTF-8', $line);
				fputcsv($fp, $line);
			}
		}
		
		fclose($fp);
	}
}
