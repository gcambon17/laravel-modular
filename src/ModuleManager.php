<?php
/**
 * Created by PhpStorm.
 * User: gcambon
 * Date: 04/04/18
 * Time: 23:28
 */

namespace Gcambon\Modules;

use Gcambon\Modules\Entities\ModuleEntity;
use Gcambon\Modules\Exceptions\ModuleInstallException;
use Gcambon\Modules\Exceptions\ModuleUninstallException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuleManager
{
	static  $NOT_FOUND_ERROR_CODE = 100;
	private $module;
	private $fileSystem;

	public function __construct(Module $module)
	{
		$this->module     = $module;
		$this->fileSystem = new Filesystem();
	}

	public function getModule()
	{
		return $this->module;
	}

	public function getDBModule()
	: ModuleEntity
	{
		$result = ModuleEntity::where('key', $this->module->getKey())
							  ->first();

		if ($result instanceof ModuleEntity) {
			return $result;
		}

		throw new \Exception("Module Not Found In DB", self::$NOT_FOUND_ERROR_CODE);
	}

	public function isActive()
	: bool
	{
		$result = false;
		try {
			$moduleDB = $this->getDBModule();
			if ($moduleDB instanceof ModuleEntity && is_null($moduleDB->deleted_at)) {
				$result = true;
			}
		}
		catch (\Exception $e) {
			if ($e->getCode() != self::$NOT_FOUND_ERROR_CODE) {
				Log::error($e->getMessage());
			}
			$result = false;
		}

		return $result;
	}

	public function active()
	: void
	{
		$modulesDbExisting = ModuleEntity::withTrashed()->where('key', $this->module->getKey())->first();
		if ($modulesDbExisting instanceof ModuleEntity) {
			if ($modulesDbExisting->trashed()) {
				$modulesDbExisting->restore();
			}
		}
		else {
			$moduleDb               = new ModuleEntity();
			$moduleDb->key          = $this->module->getKey();
			$moduleDb->class        = $this->module->getClassWithNamespace();
			$moduleDb->display_name = $this->module->getName();
			$moduleDb->save();
		}

		$this->module->postActiveActions();
	}

	public function unactive()
	: void
	{
		if ($this->isActive()) {
			$this->module->preUnactiveActions();

			$moduleDb = $this->getDBModule();
			$moduleDb->delete();
		}
	}

	public function isInstalled()
	: bool
	{
		$result = false;

		if ($this->isActive()) {
			try {
				$module = $this->getDBModule();
				if ($module->installed) {
					$result = true;
				}
			}
			catch (\Exception $e) {
				Log::error($e->getMessage());
			}
		}

		return $result;
	}

	public function install()
	: void
	{
		$this->migrateModule();
		$this->seedModule();
		$this->module->postInstallActions();

		$dbModule            = $this->getDBModule();
		$dbModule->installed = true;
		$dbModule->save();
	}

	private function getRelativeMigrationPath()
	{
		$result = preg_replace('%' . base_path() . '%', '', $this->module->getMigrationsPath());

		return $result;
	}

	private function migrateModule()
	: void
	{
		if ($this->fileSystem->exists($this->module->getMigrationsPath())) {
			$result = Artisan::call('migrate',
									array(
										'--path'  => $this->getRelativeMigrationPath(),
										'--force' => '--force',
										'--env'   => app()->environment(),
									));

			if ($result != 0) {
				throw new ModuleInstallException('Erreur lors de la migration en BD du module ' . $this->module->getName());
			}

			$lastMigration = DB::table('migrations')->orderBy('id', 'DESC')->first();
			$this->updateDBModuleWithMigrationBatch($lastMigration->batch);
		}
	}

	private function updateDBModuleWithMigrationBatch(int $migrationBatch)
	: void
	{
		$dbModule                 = $this->getDBModule();
		$dbModule->migration_step = $migrationBatch;
		$dbModule->save();
	}

	private function seedModule()
	: void
	{
		if ($this->fileSystem->exists($this->module->getDefaultSeederPath())) {
			$result = Artisan::call('db:seed',
									array(
										'--path'  => $this->module->getDefaultSeederPath(),
										'--force' => '--force',
									));
			if ($result != 0) {
				throw new ModuleInstallException('Erreur lors de la migration en BD du module ' . $this->module->getName());
			}
		}
	}

	public function uninstall()
	: void
	{
		if ($this->isInstalled()) {
			$this->module->preUninstallActions();
			$this->rollbackMigration();

			$dbModule            = $this->getDBModule();
			$dbModule->installed = false;
			$dbModule->save();
		}
	}

	private function rollbackMigration()
	: void
	{
		if ($this->fileSystem->exists($this->module->getMigrationsPath())) {
			$step       = $this->getDbModule()->migration_step;
			$app        = app();
			$repository =
				new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);

			$migrator = new ModuleMigrator($repository, $app['db'], $app['files']);
			$options  = [
				'batch' => $step,
			];

			$result = $migrator->rollback([$this->module->getMigrationsPath()], $options);
			if (!is_array($result)) {
				throw new ModuleUninstallException('Erreur lors du roll back de la migration du module ' . $this->module->getName());
			}
		}
	}
}