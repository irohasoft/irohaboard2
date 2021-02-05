<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Datasource\ConnectionManager;
use App\Vendor\Utils;
use App\Vendor\FileUpload;

/**
 * Contents Controller
 *
 * @property \App\Model\Table\ContentsTable $Contents
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsController extends AdminController
{
	/**
	 * コンテンツ一覧の表示
	 *
	 * @param int $course_id コースID
	 */
	public function index($course_id)
	{
		$course_id = intval($course_id);
		
		// コースの情報を取得
		$course = $this->Contents->Courses->get($course_id);
		
		// コンテンツ一覧を取得
		$contents = $this->Contents->find()
			->where(['course_id' => $course_id])
			->order('Contents.sort_no')
			->all();
		
		$this->set(compact('contents', 'course'));
	}

	/**
	 * コンテンツの追加
	 *
	 * @param int $course_id コースID
	 */
	public function add($course_id)
	{
		$this->edit($course_id);
		$this->render('edit');
	}

	/**
	 * コンテンツの編集
	 *
	 * @param int $course_id 所属するコースのID
	 * @param int $content_id 編集するコンテンツのID (指定しない場合、追加)
	 */
	public function edit($course_id, $content_id = null)
	{
		// コースの情報を取得
		$course = $this->Contents->Courses->get($course_id);
		
		$content = $this->Contents->getOrNew($content_id, ['contain' => []]);
		
		if($this->request->is(['patch', 'post', 'put']))
		{
			$content = $this->Contents->patchEntity($content, $this->getData());
			
			// 新規追加の場合、コンテンツの作成者と所属コースを指定
			if($this->action == 'add')
			{
				$content->user_id	= $this->readAuthUser('id');
				$content->course_id = $course_id;
				$content->sort_no	= $this->Contents->getNextSortNo($course_id);
			}
			
			if($this->Contents->save($content))
			{
				$this->Flash->success(__('コンテンツが保存されました'));

				return $this->redirect(['action' => 'index', $course_id]);
			}
			
			$this->Flash->error(__('The content could not be saved. Please, try again.'));
		}
		
		$courses = $this->Contents->Courses->find('list');
		
		$this->set(compact('course', 'content', 'courses'));
	}

	/**
	 * コンテンツの削除
	 *
	 * @param int $content_id 削除するコンテンツのID
	 */
	public function delete($content_id = null)
	{
		if(Configure::read('demo_mode'))
			return;
		
		$this->request->allowMethod(['post', 'delete']);
		
		$content = $this->Contents->get($content_id);
		
		if($this->Contents->delete($content))
		{
			// コンテンツに紐づくテスト問題も削除
			$this->LoadModel('ContentsQuestions');
			$this->ContentsQuestions->deleteAll(['ContentsQuestions.content_id' => $content_id], false);
			$this->Flash->success(__('コンテンツが削除されました'));
		}
		else
		{
			$this->Flash->error(__('The content could not be deleted. Please, try again.'));
		}

		return $this->redirect(['action' => 'index', $content->course_id]);
	}

	/**
	 * ファイル（配布資料、動画）のアップロード
	 *
	 * @param int $file_type ファイルの種類
	 */
	public function upload($file_type)
	{
		header("X-Frame-Options: SAMEORIGIN");
		
		$fileUpload = new FileUpload();

		$mode = '';
		$file_url = '';
		
		// ファイルの種類によって、アップロード可能な拡張子とファイルサイズを指定
		switch ($file_type)
		{
			case 'file' :
				$upload_extensions = (array)Configure::read('upload_extensions');
				$upload_maxsize = Configure::read('upload_maxsize');
				break;
			case 'image' :
				$upload_extensions = (array)Configure::read('upload_image_extensions');
				$upload_maxsize = Configure::read('upload_image_maxsize');
				break;
			case 'movie' :
				$upload_extensions = (array)Configure::read('upload_movie_extensions');
				$upload_maxsize = Configure::read('upload_movie_maxsize');
				break;
			default :
				throw new NotFoundException(__('Invalid access'));
		}
		
		// php.ini の upload_max_filesize, post_max_size の値を確認（互換性維持のためメソッドが存在する場合のみ）
		if(method_exists($fileUpload, 'getBytes'))
		{
			$upload_max_filesize = $fileUpload->getBytes(ini_get('upload_max_filesize'));
			$post_max_size		 = $fileUpload->getBytes(ini_get('post_max_size'));
			
			// upload_max_filesize が設定サイズより小さい場合、upload_max_filesize を優先する
			if($upload_max_filesize < $upload_maxsize)
				$upload_maxsize	= $upload_max_filesize;
			
			// post_max_size が設定サイズより小さい場合、post_max_size を優先する
			if($post_max_size < $upload_maxsize)
				$upload_maxsize	= $post_max_size;
		}
		
		$fileUpload->setExtension($upload_extensions);
		$fileUpload->setMaxSize($upload_maxsize);
		
		$original_file_name = '';
		
		if($this->request->is(['post', 'put']))
		{
			if(Configure::read('demo_mode'))
				return;
			
			$file = $this->request->getUploadedFiles()['upload_file'];
			
			// ファイルの読み込み
			$fileUpload->readFile( $file );

			$error_code = 0;
			
			// エラーチェック（互換性維持のためメソッドが存在する場合のみ）
			if(method_exists($fileUpload, 'checkFile'))
				$error_code = $fileUpload->checkFile();
			
			if($error_code > 0)
			{
				$mode = 'error';
				
				switch ($error_code)
				{
					case 1001 : // 拡張子エラー
						$this->Flash->error('アップロードされたファイルの形式は許可されていません');
						break;
					case 1002 : // ファイルサイズが0
					case 1003 : // ファイルサイズオバー
						$size = $this->request->data['Content']['file']['size'];
						$this->Flash->error('アップロードされたファイルのサイズ（'.$size.'）は許可されていません');
						break;
					default :
						$this->Flash->error('アップロード中にエラーが発生しました ('.$error_code.')');
				}
			}
			else
			{
				$original_file_name = $file->getClientFilename();

				//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"
				$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->getFileName() );

				$file_name = WWW_ROOT."uploads".DS.$new_name;										//	ファイルのパス
				$file_url = $this->webroot.'uploads/'.$new_name;									//	ファイルのURL

				$result = $fileUpload->saveFile( $file_name );										//	ファイルの保存

				if($result)																			//	結果によってメッセージを設定
				{
					//$this->Flash->success('ファイルのアップロードが完了いたしました');
					$mode = 'complete';
				}
				else
				{
					$this->Flash->error('ファイルのアップロードに失敗しました');
					$mode = 'error';
				}
			}
		}

		$this->set('mode',					$mode);
		$this->set('file_url',				$file_url);
		$this->set('file_name',				$original_file_name);
		$this->set('upload_extensions',		join(', ', $upload_extensions));
		$this->set('upload_maxsize',		$upload_maxsize);
	}
	
	/**
	 * リッチテキストエディタ(Summernote) からPOSTされた画像を保存
	 *
	 * @return string アップロードした画像のURL(JSON形式)
	 */
	public function uploadImage()
	{
		$this->autoRender = FALSE;
		
		if($this->request->is(['post', 'put']))
		{
			$fileUpload = new FileUpload();
			
			$upload_extensions = (array)Configure::read('upload_image_extensions');
			$upload_maxsize = Configure::read('upload_image_maxsize');
			
			$fileUpload->setExtension($upload_extensions);
			$fileUpload->setMaxSize($upload_maxsize);
			
			$file = $this->request->getUploadedFiles()['upload_file'];
			
			$fileUpload->readFile( $file );						//	ファイルの読み込み
			
			$new_name = date("YmdHis").$fileUpload->getExtension( $fileUpload->getFileName() );	//	ファイル名：YYYYMMDDHHNNSS形式＋"既存の拡張子"

			$file_name = WWW_ROOT."uploads".DS.$new_name;											//	ファイルのパス
			$file_url = $this->webroot.'uploads/'.$new_name;										//	ファイルのURL

			$result = $fileUpload->saveFile( $file_name );											//	ファイルの保存
			
			//debug($result);
			$response = $result ? array($file_url) : array(false);
			echo json_encode($response);
		}
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
			//debug($this->getData('id_list'));
			$this->Contents->setOrder($this->getData('id_list'));
			echo "OK";
		}
	}


	/**
	 * コンテンツのコピー
	 * @param int $course_id コピー先のコースのID
	 * @param int $content_id コピーするコンテンツのID
	 */
	public function copy($course_id, $content_id)
	{
		// コンテンツのコピー
		$old_content = $this->Contents->get($content_id);
		
		$content = $this->Contents->newEmptyEntity();
		$content = $this->Contents->patchEntity($content, $old_content->toArray());
		
		$content->status = 0;
		$content->title .= 'の複製';
		
		$this->Contents->save($content);
		
		// テスト問題のコピー
		$this->LoadModel('ContentsQuestions');
		$contentsQuestions = $this->ContentsQuestions->find()
			->where(['content_id' => $content_id])
			->order(['ContentsQuestions.sort_no' => 'asc'])
			->all();
		
		foreach($contentsQuestions as $contentsQuestion)
		{
			$this->ContentsQuestions->validate = null;
			
			$question = $this->ContentsQuestions->newEmptyEntity();
			$question = $this->ContentsQuestions->patchEntity($question, $contentsQuestion->toArray());
			
			$question->content_id	= $content->id;
			
			$conn = ConnectionManager::get('default');
			$conn->getDriver()->enableAutoQuoting();
			
			$this->ContentsQuestions->save($question);
		}
		
		return $this->redirect(['action' => 'index', $course_id]);
	}
}
