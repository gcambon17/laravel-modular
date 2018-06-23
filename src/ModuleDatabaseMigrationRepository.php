<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 14/02/17
 * Time: 08:40
 */

namespace Gcambon\Modules;


use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class ModuleDatabaseMigrationRepository extends DatabaseMigrationRepository {
	public function getAllMigrationOfBatch( $batch ) {
		$query = $this->table()
		              ->where( 'batch', '=', $batch );
		
		return $query->orderBy( 'migration', 'desc' )
		             ->get()
		             ->all();
	}
}