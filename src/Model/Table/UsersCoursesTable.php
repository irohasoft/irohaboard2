<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * UsersCourses Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\CoursesTable&\Cake\ORM\Association\BelongsTo $Courses
 *
 * @method \App\Model\Entity\UsersCourse newEmptyEntity()
 * @method \App\Model\Entity\UsersCourse newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\UsersCourse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsersCourse get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsersCourse findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\UsersCourse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsersCourse[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsersCourse|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersCourse saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsersCourse[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersCourse[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersCourse[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\UsersCourse[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersCoursesTable extends Table
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

        $this->setTable('ib_users_courses');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER',
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
            ->date('started')
            ->allowEmptyDate('started');

        $validator
            ->date('ended')
            ->allowEmptyDate('ended');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);
        $rules->add($rules->existsIn(['course_id'], 'Courses'), ['errorField' => 'course_id']);

        return $rules;
    }

	/**
	 * 学習履歴付き受講コース一覧を取得
	 * 
	 * @param int $user_id ユーザのID
	 * @return array 受講コース一覧
	 */
	public function getCourseRecord($user_id)
	{
		$sql = <<<EOF
 SELECT Course.*, Course.id, Course.title, first_date, last_date,
	   (ifnull(content_cnt, 0) - ifnull(study_cnt, 0) ) as left_cnt
   FROM ib_courses Course
   LEFT OUTER JOIN
	   (SELECT h.course_id, h.user_id,
			   MAX(DATE_FORMAT(created, '%Y/%m/%d')) as last_date,
			   MIN(DATE_FORMAT(created, '%Y/%m/%d')) as first_date
		  FROM ib_records h
		 WHERE h.user_id =:user_id
		 GROUP BY h.course_id, h.user_id) Record
	 ON Record.course_id   = Course.id
	AND Record.user_id	   =:user_id
   LEFT OUTER JOIN
		(SELECT course_id, COUNT(*) as study_cnt
		   FROM
			(SELECT r.course_id, r.content_id, COUNT(*)
			   FROM ib_records r
			  INNER JOIN ib_contents c ON r.content_id = c.id AND r.course_id = c.course_id
			  WHERE r.user_id = :user_id
				AND status = 1
			  GROUP BY r.course_id, r.content_id) as c
		 GROUP BY course_id) StudyCount
	 ON StudyCount.course_id   = Course.id
   LEFT OUTER JOIN
		(SELECT course_id, COUNT(*) as content_cnt
		   FROM ib_contents
		  WHERE kind NOT IN ('label', 'file')
			AND status = 1
		  GROUP BY course_id) ContentCount
	 ON ContentCount.course_id	 = Course.id
  WHERE id IN (SELECT course_id FROM ib_users_groups ug INNER JOIN ib_groups_courses gc ON ug.group_id = gc.group_id WHERE user_id = :user_id)
	 OR id IN (SELECT course_id FROM ib_users_courses WHERE user_id = :user_id)
  ORDER BY Course.sort_no asc
EOF;

		$params = [
			'user_id' => $user_id
		];
		
		$connection = ConnectionManager::get('default');
		$data = $connection->execute($sql, $params)->fetchAll('assoc');

		return $data;
	}
}
