<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ContentsQuestions Model
 *
 * @property \App\Model\Table\GroupsTable&\Cake\ORM\Association\BelongsTo $Groups
 * @property \App\Model\Table\ContentsTable&\Cake\ORM\Association\BelongsTo $Contents
 *
 * @method \App\Model\Entity\ContentsQuestion newEmptyEntity()
 * @method \App\Model\Entity\ContentsQuestion newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ContentsQuestion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ContentsQuestion get($primaryKey, $options = [])
 * @method \App\Model\Entity\ContentsQuestion findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ContentsQuestion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ContentsQuestion[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ContentsQuestion|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContentsQuestion saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ContentsQuestion[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ContentsQuestionsTable extends AppTable
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

        $this->setTable('ib_contents_questions');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Contents', [
            'foreignKey' => 'content_id',
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
            ->scalar('question_type')
            ->maxLength('question_type', 20)
            ->notEmptyString('question_type');

        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->notEmptyString('title');

        $validator
            ->scalar('body')
            ->requirePresence('body', 'create')
            ->notEmptyString('body');

        $validator
            ->scalar('image')
            ->maxLength('image', 200)
            ->allowEmptyFile('image');

        $validator
            ->scalar('options')
            ->maxLength('options', 200)
            ->allowEmptyString('options');

        $validator
            ->scalar('correct')
            ->maxLength('correct', 200)
            ->notEmptyString('correct');

        $validator
            ->integer('score')
            ->notEmptyString('score');

        $validator
            ->scalar('explain')
            ->allowEmptyString('explain');

        $validator
            ->scalar('comment')
            ->allowEmptyString('comment');

        $validator
            ->integer('sort_no')
            ->notEmptyString('sort_no');

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
        $rules->add($rules->existsIn(['content_id'], 'Contents'), ['errorField' => 'content_id']);

        return $rules;
    }


	/**
	 * 問題の並べ替え
	 * 
	 * @param array $id_list 問題のIDリスト（並び順）
	 */
	public function setOrder($id_list)
	{
		for($i=0; $i< count($id_list); $i++)
		{
			$sql = "UPDATE ib_contents_questions SET sort_no = :sort_no WHERE id= :id";

			$params = array(
				'sort_no' => ($i+1),
				'id' => $id_list[$i]
			);

			$this->db_execute($sql, $params);
		}
	}
}
