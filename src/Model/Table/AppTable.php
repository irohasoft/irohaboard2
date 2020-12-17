<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * App Model
 *
 */
class AppTable extends Table
{
	public function db_query($sql, $params)
	{
		$connection = ConnectionManager::get('default');
		$data = $connection->execute($sql, $params)->fetchAll('assoc');

		return $data;
	}
	
	public function db_execute($sql, $params)
	{
		$connection = ConnectionManager::get('default');
		$connection->execute($sql, $params);
	}
	
	public function db_query_value($sql, $params, $field_name)
	{
		$data = $this->db_query($sql, $params, $field_name);
		
		$list = array();
		
		for($i=0; $i< count($data); $i++)
		{
			$list[$i] = $data[$i]['user_id'];
		}
		
		return $list;
	}
}
