<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 14/02/17
 * Time: 08:29
 */

namespace Gcambon\Modules;


use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Arr;

class ModuleMigrator extends Migrator {
	
	public function rollback( $paths = [], array $options = [] ) {
		$this->notes = [];
		
		$rolledBack = [];
		
		// We want to pull in the last batch of migrations that ran on the previous
		// migration operation. We'll then reverse those migrations and run each
		// of them "down" to reverse the last migration "operation" which ran.
		if ( ( $batch = Arr::get( $options, 'batch', 0 ) ) > 0 ) {
			$migrations = $this->repository->getAllMigrationOfBatch( $batch );
		} else {
			$migrations = $this->repository->getLast();
		}
		
		$count = count( $migrations );
		
		$files = $this->getMigrationFiles( $paths );
		
		if ( $count === 0 ) {
			$this->note( '<info>Nothing to rollback.</info>' );
		} else {
			// Next we will run through all of the migrations and call the "down" method
			// which will reverse each migration in order. This getLast method on the
			// repository already returns these migration's names in reverse order.
			$this->requireFiles( $files );
			
			foreach ( $migrations as $migration ) {
				$migration = (object) $migration;
				
				$rolledBack[] = $files[ $migration->migration ];
				
				$this->runDown( $files[ $migration->migration ],
				                $migration,
				                Arr::get( $options, 'pretend', false ) );
			}
		}
		
		return $rolledBack;
	}
	
	
}