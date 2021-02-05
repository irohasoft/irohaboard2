<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;


/**
 * Infos Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsToMany $Groups
 *
 * @method \App\Model\Entity\Info newEmptyEntity()
 * @method \App\Model\Entity\Info newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Info[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Info get($primaryKey, $options = [])
 * @method \App\Model\Entity\Info findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Info patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Info[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Info|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Info saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Info[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InfosTable extends AppTable
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

		$this->setTable('ib_infos');
		$this->setDisplayField('title');
		$this->setPrimaryKey('id');

		$this->addBehavior('Timestamp');

		$this->belongsTo('Users', [
			'foreignKey' => 'user_id',
			'joinType' => 'LEFT OUTER',
		]);
		$this->belongsTo('Groups', [
			'foreignKey' => 'group_id',
			'joinType' => 'INNER',
		]);
		$this->belongsToMany('Groups', [
			'foreignKey' => 'info_id',
			'targetForeignKey' => 'group_id',
			'joinTable' => 'infos_groups',
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
			->scalar('title')
			->maxLength('title', 200)
			->requirePresence('title', 'create')
			->notEmptyString('title');

		$validator
			->scalar('body')
			->allowEmptyString('body');

		$validator
			->dateTime('opened')
			->allowEmptyDateTime('opened');

		$validator
			->dateTime('closed')
			->allowEmptyDateTime('closed');

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
		$rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
		$rules->add($rules->existsIn(['group_id'], 'Groups'), ['errorField' => 'user_id']);

		return $rules;
	}

	/**
	 * お知らせ一覧を取得（エイリアス）
	 * 
	 * @param int $user_id ユーザID
	 * @param int $limit 取得件数
	 * @return array お知らせ一覧
	 */
	public function getInfos($user_id, $limit = null)
	{
		$infos = $this->getInfoOption($user_id, $limit);
		return $infos;
	}
	
	/**
	 * お知らせ一覧を取得
	 * 
	 * @param int $user_id ユーザID
	 * @param int $limit 取得件数
	 * @return array お知らせ一覧
	 */
	public function getInfoOption($user_id, $limit = null)
	{
		$sql = <<<EOF
	SELECT
		Info.id,
		Info.title,
		Info.created 
	FROM
		ib_infos AS Info
		LEFT OUTER JOIN ib_infos_groups AS InfoGroup ON ( Info.id = InfoGroup.info_id ) 
	WHERE
		InfoGroup.group_id IS NULL 
		OR InfoGroup.group_id IN ( SELECT group_id FROM ib_users_groups WHERE user_id = :user_id ) 
	GROUP BY
		Info.id,
		Info.title,
		Info.created 
	ORDER BY
		Info.created DESC 
EOF;
		if($limit)
			$sql .= ' LIMIT '.intval($limit);
		
		//debug($sql);
		
		$params = [
			'user_id' => $user_id,
		];
		
		$connection = ConnectionManager::get('default');
		$data = $connection->execute($sql, $params)->fetchAll('assoc');

		//debug($data);
		return $data;
	}
}
