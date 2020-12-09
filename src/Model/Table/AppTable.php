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
	public function my_query($sql, $params)
	{
		$connection = ConnectionManager::get('default');
		$data = $connection->execute($sql, $params)->fetchAll('assoc');

		return $data;
	}
}
