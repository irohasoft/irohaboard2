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
class UsersTable extends Table
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
            ->maxLength('username', 50)
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 200)
            ->notEmptyString('password');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->notEmptyString('name');

        $validator
            ->scalar('role')
            ->maxLength('role', 20)
            ->notEmptyString('role');

        $validator
            ->email('email')
            ->notEmptyString('email');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
}
