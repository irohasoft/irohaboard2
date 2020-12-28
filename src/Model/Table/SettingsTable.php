<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Settings Model
 *
 * @method \App\Model\Entity\Setting newEmptyEntity()
 * @method \App\Model\Entity\Setting newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Setting[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Setting get($primaryKey, $options = [])
 * @method \App\Model\Entity\Setting findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Setting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Setting[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Setting|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setting saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Setting[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SettingsTable extends AppTable
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

        $this->setTable('ib_settings');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('setting_key')
            ->maxLength('setting_key', 100)
            ->requirePresence('setting_key', 'create')
            ->notEmptyString('setting_key');

        $validator
            ->scalar('setting_name')
            ->maxLength('setting_name', 100)
            ->requirePresence('setting_name', 'create')
            ->notEmptyString('setting_name');

        $validator
            ->scalar('setting_value')
            ->maxLength('setting_value', 1000)
            ->requirePresence('setting_value', 'create')
            ->notEmptyString('setting_value');

        return $validator;
    }

	/**
	 * システム設定の値を取得
	 * @param int $setting_key 設定キー
	 * @return string 設定値
	 */
	public function getSettingValue($setting_key)
	{
		$setting_value = "";
		
		$sql = <<<EOF
SELECT setting_value
  FROM ib_settings
 WHERE setting_key = :setting_key
EOF;
		$params = [
				'setting_key' => $setting_key
		];
		
		$data = $this->query($sql, $params);
		
		
		return $setting_value;
	}
	
	/**
	 * システム設定の値のリストを取得
	 * @return array 設定値リスト（連想配列）
	 */
	public function getSettings()
	{
		$result = [];
		
		$settings = $this->find('all')->toList();
		
		foreach ($settings as $setting)
		{
			$result[$setting['setting_key']] = $setting['setting_value'];
		}
		
		return $result;
	}
	
	/**
	 * システム設定を保存
	 * @param array 保存する設定値リスト（連想配列）
	 */
	public function setSettings($settings)
	{
		foreach ($settings as $key => $value)
		{
			$params = [
				'setting_key' => $key,
				'setting_value' => $value
			];
			
			$this->db_execute("UPDATE ib_settings SET setting_value = :setting_value WHERE setting_key = :setting_key", $params);
		}
	}
}
