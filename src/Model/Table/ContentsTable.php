<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contents Model
 *
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\BelongsTo $Courses
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\ContentsQuestionsTable&\Cake\ORM\Association\HasMany $ContentsQuestions
 * @property \App\Model\Table\RecordsTable&\Cake\ORM\Association\HasMany $Records
 *
 * @method \App\Model\Entity\Content newEmptyEntity()
 * @method \App\Model\Entity\Content newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Content[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Content get($primaryKey, $options = [])
 * @method \App\Model\Entity\Content findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Content patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Content[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Content|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Content saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Content[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContentsTable extends AppTable
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

        $this->setTable('ib_contents');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasMany('ContentsQuestions', [
            'foreignKey' => 'content_id',
        ]);
        $this->hasMany('Records', [
            'foreignKey' => 'content_id',
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
            ->notEmptyString('title');

        $validator
            ->scalar('url')
            ->maxLength('url', 200)
            ->allowEmptyString('url');

        $validator
            ->scalar('kind')
            ->maxLength('kind', 20)
            ->notEmptyString('kind');

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->integer('timelimit')
            ->allowEmptyString('timelimit');

        $validator
            ->integer('pass_rate')
            ->allowEmptyString('pass_rate');

        $validator
            ->dateTime('opened')
            ->allowEmptyDateTime('opened');

        $validator
            ->dateTime('deleted')
            ->allowEmptyDateTime('deleted');

        $validator
            ->integer('sort_no')
            ->notEmptyString('sort_no');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

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
        $rules->add($rules->existsIn(['course_id'], 'Courses'), ['errorField' => 'course_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }

	/**
	 * 学習履歴付きコンテンツ一覧を取得
	 * 
	 * @param int $user_id   取得対象のユーザID
	 * @param int $course_id 取得対象のコースID
	 * @param string $role   取得者の権限（admin の場合、非公開のコンテンツも取得）
	 * @var array 学習履歴付きコンテンツ一覧
	 */
	public function getContentRecord($user_id, $course_id, $role = 'user')
	{
		$sql = <<<EOF
 SELECT Content.*, first_date, last_date, record_id, Record.study_sec, Record.study_count,
       (SELECT understanding
          FROM ib_records h1
         WHERE h1.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as understanding,
       (SELECT ifnull(is_passed, 0)
          FROM ib_records h2
         WHERE h2.id = Record.record_id
         ORDER BY created
          DESC LIMIT 1) as is_passed
   FROM ib_contents Content
   LEFT OUTER JOIN
       (SELECT h.content_id, h.user_id,
               MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
               MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date,
			   MAX(id) as record_id,
			   SUM(ifnull(study_sec, 0)) as study_sec,
			   COUNT(*) as study_count
		  FROM ib_records h
         WHERE h.user_id    =:user_id
		   AND h.course_id  =:course_id
         GROUP BY h.content_id, h.user_id) Record
     ON Record.content_id  = Content.id
    AND Record.user_id     =:user_id
  WHERE Content.course_id  =:course_id
    AND (status = 1 OR 'admin' = :role)
  ORDER BY Content.sort_no
EOF;

		$params = [
				'user_id' => $user_id,
				'course_id' => $course_id,
				'role' => $role
		];

		$data = $this->db_query($sql, $params);

		return $data;
	}

	/**
	 * コンテンツの並べ替え
	 * 
	 * @param array $id_list コンテンツのIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$sql = "UPDATE ib_contents SET sort_no = :sort_no WHERE id= :id";

			$params = [
				'sort_no' => ($i+1),
				'id' => $id_list[$i]
			];

			$this->db_execute($sql, $params);
		}
	}

	/**
	 * 新規追加時のコンテンツのソート番号を取得
	 * 
	 * @param array $course_id コースID
	 * @return int ソート番号
	 */
	public function getNextSortNo($course_id)
	{
		$row = $this->find()
			->where(['Contents.course_id' => $course_id])
			->order(['Contents.sort_no' => 'DESC'])
			->limit(1)
			->first();
		
		if(!$row)
			return 1;
		
		return ($row->sort_no + 1);
	}

}
