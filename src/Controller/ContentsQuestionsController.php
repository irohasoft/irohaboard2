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

namespace App\Controller;

/**
 * ContentsQuestions Controller
 *
 * @property \App\Model\Table\ContentsQuestionsTable $ContentsQuestions
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContentsQuestionsController extends AppController
{
	/**
	 * 問題を出題
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID (テスト結果表示の場合、指定)
	 */
	public function index($content_id, $record_id = null)
	{
		//------------------------------//
		//	コンテンツ情報を取得		//
		//------------------------------//
		$this->loadModel('Contents');
		$content = $this->Contents->get($content_id, ['contain' => ['Courses']]);
		
		//------------------------------//
		//	権限チェック				//
		//------------------------------//
		// 管理者以外の場合、コンテンツの閲覧権限の確認
		if(!$this->isAdminPage())
		{
			$this->loadModel('Courses');
			
			if(!$this->Courses->hasRight($this->readAuthUser('id'), $content->course_id))
				throw new NotFoundException(__('Invalid access'));
		}
		
		//------------------------------//
		//	問題情報を取得				//
		//------------------------------//
		$record = null;
		
		if($record_id != null) // テスト結果表示モードの場合
		{
			// テスト結果情報を取得
			$this->loadModel('Records');
			$record = $this->Records->get($record_id, [
				'contain' => ['Courses', 'RecordsQuestions'],
			]);
			
			// 受講者によるテスト結果表示の場合、自身のテスト結果か確認
			if(!$this->isAdminRecordPage() && $this->isRecordPage() && ($record->user_id != $this->readAuthUser('id')))
			{
				throw new NotFoundException(__('Invalid access'));
			}
			
			// テスト結果に紐づく問題ID一覧（出題順）を作成
			// 問題が存在しない場合のエラーを防ぐため、0を追加
			$question_id_list = [0];
			
			foreach($record->records_questions as $question)
			{
				$question_id_list[] = $question->question_id;
			}
			
			// 問題ID一覧を元に問題情報を取得
			$contentsQuestions = $this->ContentsQuestions->find()
				->where(['content_id' => $content_id, 'ContentsQuestions.id IN' => $question_id_list])
				->order('FIELD(ContentsQuestions.id,'.implode(',', $question_id_list).')') // 指定したID順で並び替え
				->all();
		}
		else if($this->readSession('Iroha.RondomQuestions.'.$content_id.'.id_list') != null) // 既にランダム出題情報がセッション上にある場合
		{
			// セッションにランダム出題情報が存在する場合、その情報を使用
			$question_id_list = $this->readSession('Iroha.RondomQuestions.'.$content_id.'.id_list');
			
			$contentsQuestions = $this->ContentsQuestions->find()
				->where(['content_id' => $content_id, 'ContentsQuestions.id IN' => $question_id_list])
				->order('FIELD(ContentsQuestions.id,'.implode(',', $question_id_list).')') // 指定したID順で並び替え
				->all();
		}
		else if($content->question_count > 0) // ランダム出題の場合
		{
			// ランダム出題情報を取得
			$contentsQuestions = $this->ContentsQuestions->find()
				->where(['content_id' => $content_id])
				->limit($content->question_count) // 出題数
				->order('rand()')// 乱数で並び替え
				->all();
			
			// 問題IDの一覧を作成
			$question_id_list = [];
			
			foreach($contentsQuestions as $contentsQuestion)
			{
				$question_id_list[] = $contentsQuestion->id;
			}
			
			// ランダム出題情報を一時的にセッションに格納（リロードによる変化や、採点時の問題情報との矛盾を防ぐため）
			$this->writeSession('Iroha.RondomQuestions.'.$content_id.'.id_list', $question_id_list);
		}
		else // 通常の出題の場合
		{
			// 全ての問題情報を取得（通常の処理）
			$contentsQuestions = $this->ContentsQuestions->find()
				->where(['content_id' => $content_id])
				->order('ContentsQuestions.sort_no asc')
				->all();
		}
		
		//------------------------------//
		//	採点処理					//
		//------------------------------//
		if($this->request->is('post'))
		{
			$details	= [];									// 成績詳細情報
			$full_score	= 0;									// 最高点
			$pass_score	= 0;									// 合格基準点
			$my_score	= 0;									// 得点
			$pass_rate	= $content->pass_rate;					// 合格得点率
			
			//------------------------------//
			//	成績の詳細情報の作成		//
			//------------------------------//
			foreach($contentsQuestions as $contentsQuestion)
			{
				$question_id	= $contentsQuestion->id;							// 問題ID
				$answer			= $this->getData('answer_'.$question_id);			// 解答（複数選択問題の場合、配列）
				
				$correct		= $contentsQuestion->correct;						// 正解
				$corrects		= explode(',', $correct);							// 複数選択問題の正解（配列）
				
				$score			= $contentsQuestion->score;							// 配点
				
				// 複数選択問題の場合
				if(count($corrects) > 1)
				{
					// 全ての解答と正解が一致するか確認
					$is_correct	= $this->isMultiCorrect($answer, $corrects) ? 1 : 0;
					
					// データベース格納用に解答をカンマ区切りの文字列に変更
					$answer		= is_array($answer) ? implode(',', $answer) : null;
				}
				else
				{
					$is_correct	= ($answer == $correct) ? 1 : 0;
				}
				
				// 合計点（配点の合計）
				$full_score += $score;
				
				// 得点（正解した問題の配点の合計）
				if($is_correct == 1)
					$my_score += $score;
				
				// 問題の正誤
				$details[] = [
					'question_id'	=> $question_id,	// 問題ID
					'answer'		=> $answer,			// 解答
					'correct'		=> $correct,		// 正解
					'is_correct'	=> $is_correct,		// 正誤
					'score'			=> $score,			// 配点
				];
			}
			
			// 合格基準得点
			$pass_score = ($full_score * $pass_rate) / 100;
			
			// 合格基準得点を超えていた場合、合格とする
			$is_passed = ($my_score >= $pass_score) ? 1 : 0;
			
			// テスト実施時間
			$study_sec = $this->getData('study_sec');
			
			// 追加する成績情報
			$data = [
				'user_id'		=> $this->readAuthUser('id'),					// ログインユーザのユーザID
				'course_id'		=> $content->course->id,						// コースID
				'content_id'	=> $content_id,									// コンテンツID
				'full_score'	=> $full_score,									// 合計点
				'pass_score'	=> $pass_score,									// 合格基準得点
				'score'			=> $my_score,									// 得点
				'is_passed'		=> $is_passed,									// 合否判定
				'study_sec'		=> $study_sec,									// テスト実施時間
				'is_complete'	=> 1
			];
			
			$this->loadModel('Records');
			$record = $this->Records->newEmptyEntity();
			$record = $this->Records->patchEntity($record, $data);
			
			//------------------------------//
			//	テスト結果の保存			//
			//------------------------------//
			if($this->Records->save($record))
			{
				$this->loadModel('RecordsQuestions');
				
				// 問題単位の成績を保存
				foreach($details as $detail)
				{
					$detail['record_id'] = $record->id;
					$new_data = $this->RecordsQuestions->newEmptyEntity();
					$new_data = $this->RecordsQuestions->patchEntity($new_data, $detail);
					
					//debug($new_data);
					$this->RecordsQuestions->save($new_data);
				}
				
				// ランダム出題用の問題IDリストを削除
				$this->deleteSession('Iroha.RondomQuestions.'.$content_id.'.id_list');
				
				$this->redirect([
					'action' => 'record',
					$content_id,
					$record->id
				]);
			}
		}
		
		$is_record = $this->isRecordPage();	// テスト結果表示フラグ
		$is_admin_record = $this->isAdminRecordPage();
		
		$this->set(compact('content', 'contentsQuestions', 'record', 'is_record', 'is_admin_record'));
	}

	/**
	 * テスト結果を表示
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function record($content_id, $record_id)
	{
		$this->index($content_id, $record_id);
		$this->render('index');
	}

	/**
	 * テスト結果を表示（管理者用）
	 * @param int $content_id 表示するコンテンツ(テスト)のID
	 * @param int $record_id 履歴ID
	 */
	public function adminRecord($content_id, $record_id)
	{
		if($this->readAuthUser('role') != 'admin')
			return;
		
		$this->index($content_id, $record_id);
		$this->render('index');
	}

	/**
	 * 複数選択問題の正誤判定
	 * @param int $answers 受講者が選択した選択肢リスト
	 * @param int $corrects 正解リスト
	 * @return bool true : 正解 / false : 不正解
	 */
	private function isMultiCorrect($answers, $corrects)
	{
		// 解答が設定されていない場合、不正解
		if(!isset($answers))
			return false;
		
		// 解答がnullの場合、不正解
		if($answers == null)
			return false;
		
		// 解答数と正解数が一致しない場合、不正解
		if(count($answers) != count($corrects))
			return false;
		
		// 解答が正解に含まれるか確認
		for($i =0; $i < count($answers); $i++)
		{
			if(!in_array($answers[$i], $corrects))
				return false;
		}
		
		// 全て含まれていれば正解
		return true;
	}
}
