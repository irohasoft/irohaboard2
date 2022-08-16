<?php
/**
 * @author        Kotaro Miura
 * @copyright     2015-2021 iroha Soft, Inc. (https://irohasoft.jp)
 */

declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

/**
 * Install Controller
 */
class InstallController extends AppController
{
	var $err_msg = '';
	var $db   = null;
	var $path = '';
	
	/**
	 * AppController の beforeFilter をオーバーライド ※インストールできなくなる為、この function を消さないこと
	 */
	public function beforeFilter(\Cake\Event\EventInterface $event)
	{
		$this->Authentication->allowUnauthenticated(['index', 'installed', 'complete', 'error']);
	}
	
	/**
	 * インストール
	 */
	public function index()
	{
		try
		{
			// apache_get_modules が存在する場合のみ、Apache のモジュールチェックを行う
			if(function_exists('apache_get_modules'))
			{
				// mod_rewrite 存在チェック
				if(!$this->__apache_module_loaded('mod_rewrite'))
				{
					// エラー出力
					$this->err_msg = 'Apache モジュール mod_rewrite がロードされていません';
					$this->error();
					$this->render('error');
					return;
				}
				
				// mod_headers 存在チェック
				if(!$this->__apache_module_loaded('mod_headers'))
				{
					// エラー出力
					$this->err_msg = 'Apache モジュール mod_headers がロードされていません';
					$this->error();
					$this->render('error');
					return;
				}
			}
			
			// mbstring 存在チェック
			if(!extension_loaded('mbstring'))
			{
				// エラー出力
				$this->err_msg = 'PHP モジュール mbstring がロードされていません';
				$this->error();
				$this->render('error');
				return;
			}
			
			// pdo_mysql 存在チェック
			if(!extension_loaded('pdo_mysql'))
			{
				// エラー出力
				$this->err_msg = 'PHP モジュール pdo_mysql がロードされていません';
				$this->error();
				$this->render('error');
				return;
			}
		}
		catch(\Exception $e)
		{
			$this->err_msg = '各種モジュール（mod_rewrite, mod_headers, mbstring, pdo_mysql）チェック中にエラーが発生いたしました。';
			$this->error();
			$this->render('error');
			return;
		}

		try
		{
			$this->db   = ConnectionManager::get('default');
			
			//debug($db);
			$sql = "SHOW TABLES FROM `".$this->db->config('default')['database']."` LIKE 'ib_users'";
			$data = $this->db->query($sql);

			// ユーザテーブルが存在する場合、インストール済みと判断
			if(count($data) > 0)
			{
				$this->render('installed');
			}
			else
			{
				if($this->getData())
				{
					$data = $this->getData();
					$username	= $data['username'];
					$password	= $data['password'];
					$password2	= $data['password2'];
					
					if((strlen($username) < 4)||(strlen($username) > 32))
					{
						$this->Flash->error('ログインIDは4文字以上32文字以内で入力して下さい');
						return;
					}
					
					if(!preg_match("/^[a-zA-Z0-9]+$/", $username))
					{
						$this->Flash->error('ログインIDは英数字で入力して下さい');
						return;
					}
					
					if((strlen($password) < 4)||(strlen($password) > 32))
					{
						// エラー出力
						$this->Flash->error('パスワードは4文字以上32文字以内で入力して下さい');
						return;
					}
					
					if($password != $password2)
					{
						// エラー出力
						$this->Flash->error('パスワードと確認用パスワードが一致しません');
						return;
					}
					
					if(!preg_match("/^[a-zA-Z0-9]+$/", $password))
					{
						$this->Flash->error('パスワードは英数字で入力して下さい');
						return;
					}
					
					// インストールの実行
					$this->__install();
					
					// 初期管理者アカウントの存在確認および作成
					$this->__createRootAccount($username, $password);
				}
			}
		}
		catch(\Exception $e)
		{
			$this->err_msg = 'データベースへの接続に失敗しました。データベース設定ファイル(config/database.php)をご確認ください。';
			$this->error();
			$this->render('error');
		}
	}
	
	/**
	 * インストール済みメッセージを表示
	 */
	public function installed()
	{
	}
	
	/**
	 * インストール完了メッセージを表示
	 */
	public function complete()
	{
	}
	
	/**
	 * インストールエラーメッセージを表示
	 */
	public function error()
	{
		$this->set('body', $this->err_msg);
	}
	
	
	/**
	 * インストールの実行
	 */
	private function __install()
	{
		// 各種テーブルの作成
		$this->path = CONFIG.'schema'.DS.'app.sql';
		$err_statements = $this->__executeSQLScript();
		
		// クエリ実行中にエラーが発生した場合、ログファイルにエラー内容を記録
		if(count($err_statements) > 0)
		{
			$this->err_msg = 'インストール実行中にエラーが発生しました。詳細はエラーログ(tmp/logs/error.log)をご確認ください。';
			
			foreach($err_statements as $err)
			{
				$err .= $err."\n";
			}
			
			// エラー出力
			$this->log($err);
			$this->error();
			$this->render('error');
			return;
		}
		else
		{
			$this->complete();
			$this->render('complete');
		}
	}
	
	/**
	 * app.sql のクエリの実行
	 */
	private function __executeSQLScript()
	{
		$statements = file_get_contents($this->path);
		$statements = explode(';', $statements);
		$err_statements = [];
		
		foreach($statements as $statement)
		{
			if(trim($statement) != '')
			{
				try
				{
					$this->db->query($statement);
				}
				catch (PDOException $e)
				{
					// カラム重複追加エラー
					if($e->errorInfo[0] == '42S21')
						continue;
					
					// ビュー重複追加エラー
					if($e->errorInfo[0] == '42S01')
						continue;
					
					$error_msg = sprintf("%s\n[Error Code]%s\n[Error Code2]%s\n[SQL]%s", $e->errorInfo[2], $e->errorInfo[0], $e->errorInfo[1], $statement);
					$err_statements[count($err_statements)] = $error_msg;
				}
			}
		}
		
		return $err_statements;
	}
	
	/**
	 * rootアカウントの作成
	 */
	private function __createRootAccount($username, $password)
	{
		// 管理者アカウントの存在確認
		$this->loadModel('Users');
		$user = $this->Users->find()
			->where(['Users.role' => 'admin'])
			->first();
		
		//debug($data);
		if(!$user)
		{
			// 管理者アカウントが存在しない場合のみ、初期管理者アカウントを作成
			$data = [
				'username' => $username,
				'password' => $password,
				'name' => $username,
				'role' => 'admin',
				'email' => 'info@example.com'
			];

			$user = $this->Users->newEmptyEntity();
			$user = $this->Users->patchEntity($user, $data);
			
			$this->Users->save($user);
		}
	}
	
	/**
	 * Apache のモジュールのロードをチェック
	 */
	private function __apache_module_loaded($module_name)
	{
		$modules = apache_get_modules();
		
		foreach($modules as $module)
		{
			if($module == $module_name)
				return true;
		}
		
		return false;
	}

}
