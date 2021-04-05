<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\ContentsTable&\Cake\ORM\Association\HasMany $Contents
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\HasMany $Courses
 * @property \App\Model\Table\InfosTable&\Cake\ORM\Association\HasMany $Infos
 * @property \App\Model\Table\LogsTable&\Cake\ORM\Association\HasMany $Logs
 * @property \App\Model\Table\RecordsTable&\Cake\ORM\Association\HasMany $Records
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\BelongsToMany $Courses
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsToMany $Groups
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends AppTable
{
	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config): void
	{
		parent::initialize($config);

		$this->setTable('ib_users');
		$this->setDisplayField('name');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');
		
		// ビヘイビア（friendsofcake/search）の追加
		$this->addBehavior("Search.Search");
		
		// Setup search filter using search manager
		/*
		$this->searchManager()
			->value('group_id', ['filterEmpty' => false,])
			// Here we will alias the 'q' query param to search the `Articles.title`
			// field and the `Articles.content` field, using a LIKE match, with `%`
			// both before and after.
			->add('q', 'Search.Like', [
				'before' => true,
				'after' => true,
				'fieldMode' => 'OR',
				'comparison' => 'LIKE',
				'wildcardAny' => '*',
				'wildcardOne' => '?',
				'fields' => ['group_id', 'username'],
			])
			->add('foo', 'Search.Callback', [
				'callback' => function (\Cake\ORM\Query $query, array $args, \Search\Model\Filter\Base $filter) {
					// Modify $query as required
				}
		]);
		*/
		
		$this->hasMany('Contents', [
			'foreignKey' => 'user_id',
		]);
		$this->hasMany('Courses', [
			'foreignKey' => 'user_id',
		]);
		$this->hasMany('Infos', [
			'foreignKey' => 'user_id',
		]);
		$this->hasMany('Logs', [
			'foreignKey' => 'user_id',
		]);
		$this->hasMany('Records', [
			'foreignKey' => 'user_id',
		]);
		$this->belongsToMany('Courses', [
			'foreignKey' => 'user_id',
			'targetForeignKey' => 'course_id',
			'joinTable' => 'users_courses',
		]);
		$this->belongsToMany('Groups', [
			'foreignKey' => 'user_id',
			'targetForeignKey' => 'group_id',
			'joinTable' => 'users_groups',
		]);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator): Validator
	{
		$validator
			->integer('id')
			->allowEmptyString('id', null, 'create');

		$validator
			->scalar('username')
			->notEmptyString('username')
			->add('username', 'length', ['rule' => ['lengthBetween', 4, 50], 'message' => 'ログインIDは4文字以上32文字以内で入力して下さい'])
			->add('username', 'unique', ['rule' => 'validateUnique','provider' => 'table', 'message' => 'ログインIDが重複しています'])
			->add('username', 'custom', ['rule' => [$this, 'alphaNumericMB'], 'message' => 'ログインIDは英数字で入力して下さい']);

		$validator
			->scalar('password')
			->add('password', 'length', ['rule' => ['lengthBetween', 4, 50], 'message' => 'パスワードは4文字以上32文字以内で入力して下さい'])
			->add('password', 'custom', ['rule' => [$this, 'alphaNumericMB'], 'message' => 'パスワードは英数字で入力して下さい'])
			->allowEmptyString('password');

		$validator
			->scalar('new_password')
			->add('new_password', 'length', ['rule' => ['lengthBetween', 4, 50], 'message' => 'パスワードは4文字以上32文字以内で入力して下さい'])
			->add('new_password', 'custom', ['rule' => [$this, 'alphaNumericMB'], 'message' => 'パスワードは英数字で入力して下さい'])
			->allowEmptyString('new_password');


		$validator
			->scalar('name')
			->maxLength('name', 50)
			->notEmptyString('name');

		$validator
			->scalar('role')
			->notEmptyString('role');

		$validator
			->email('email')
			->allowEmptyString('email');

		$validator
			->scalar('comment')
			->allowEmptyString('comment');

		$validator
			->dateTime('last_logined')
			->allowEmptyDateTime('last_logined');

		$validator
			->dateTime('started')
			->allowEmptyDateTime('started');

		$validator
			->dateTime('ended')
			->allowEmptyDateTime('ended');

		$validator
			->dateTime('deleted')
			->allowEmptyDateTime('deleted');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules): RulesChecker
	{
		$rules->add($rules->isUnique(['username']), ['errorField' => 'username']);
//		$rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

		return $rules;
	}

	/**
	 * 学習履歴の削除
	 * 
	 * @param int array $user_id 学習履歴を削除するユーザのID
	 */
	public function deleteUserRecords($user_id)
	{
		$sql = 'DELETE FROM ib_records_questions WHERE record_id IN (SELECT id FROM ib_records WHERE user_id = :user_id)';
		
		$params = [
			'user_id' => $user_id,
		];
		
		$this->db_execute($sql, $params);
		
		
		$this->Records = new RecordsTable();
		$this->Records->deleteAll(['Records.user_id' => $user_id], false);
	}
}
