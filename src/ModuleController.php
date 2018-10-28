<?php

namespace Gcambon\Modules;

use Gcambon\Modules\Entities\ModuleEntity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class ModuleController extends \App\Http\Controllers\Controller
{
	public function index()
	{
		$moduleLoader   = App::getFacadeApplication()->ModuleLoader;
		$modules        = $moduleLoader->getAllModules();
		$managedModules = new Collection();
		foreach ($modules as $module) {
			$managedModules->push(new ModuleManager($module));
		}

		return view('laravel-modular::index')->with('modules', $managedModules);
	}

	public function activate($moduleKey)
	{
		$moduleLoader = App::getFacadeApplication()->ModuleLoader;
		$modules      = $moduleLoader->getAllModules();
		foreach ($modules as $module) {
			if ($moduleKey == $module->getKey()) {
				$manager = new ModuleManager($module);
				$manager->active();
			}
		}

		return redirect(url('laravel-modular'));
	}

	public function desactivate($moduleKey)
	{
		$moduleDb = ModuleEntity::where('key', $moduleKey)->first();

		if ($moduleDb instanceof ModuleEntity) {
			$moduleDb->delete();
		}

		return redirect(url('laravel-modular'));
	}

	public function install($moduleKey)
	{
		$moduleDb = ModuleEntity::where('key', $moduleKey)->first();

		if ($moduleDb instanceof ModuleEntity) {
			$manager = new ModuleManager(new $moduleDb->class());
			$manager->install();
		}

		return redirect(url('laravel-modular'));
	}

	public function uninstall($moduleKey)
	{
		$moduleDb = ModuleEntity::where('key', $moduleKey)->first();

		if ($moduleDb instanceof ModuleEntity) {
			$manager = new ModuleManager(new $moduleDb->class());
			if($manager->isInstalled()) {
				$manager->uninstall();
			}
		}

		return redirect(url('laravel-modular'));
	}
}