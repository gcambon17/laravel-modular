<?php

namespace Gcambon\Modules;

use App\Providers\EventServiceProvider;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class ModulesServiceProvider extends EventServiceProvider
{
	public function boot()
	{
		$this->publish();

		$this->app->singleton('ModuleLoader', function ($app) { return new ModuleLoader(); });
		$modules = $this->app->ModuleLoader->getAllModules();
		foreach ($modules as $module) {
			$manager = new ModuleManager($module);
			if($manager->isActive()) {
				$this->loadViews($module);
				$this->loadRoutes($module);
				$this->loadTranslations($module);
				$this->subscribeEvents($module);
			}
		}
	}

	private function publish()
	{
		$this->loadRoutesFrom(__DIR__.'/routes.php');
		$this->loadViewsFrom(__DIR__.'/../views', 'laravel-modular');

		$this->publishes([
							 __DIR__.'/../views/' => resource_path('views/vendor/laravel-modular'),
						 ]);
		$this->publishes([
							 __DIR__ . '/../database/migrations/' => database_path('migrations'),
						 ], 'migrations');
		$this->publishes([
							 __DIR__.'/../config/laravel-modular.php' => config_path('laravel-modular.php'),
						 ], 'config');
	}

	private function loadViews(Module $module)
	{
		$viewsPaths = $module->getViewsPaths();
		$fileSystem = new Filesystem();
		if (is_array($viewsPaths) && count($viewsPaths) > 0) {
			foreach ($viewsPaths as $viewPath) {
				if ($fileSystem->exists($viewPath)) {
					View::addNamespace($module->getName(), $viewPath);
				}
			}
		}
	}

	private function loadRoutes(Module $module)
	{
		$routesPath = $module->getRoutesPath();
		$fileSystem = new Filesystem();
		if ($routesPath != null) {
			if ($fileSystem->exists($routesPath)) {
				foreach ($fileSystem->files($routesPath) as $routeFile) {
					$fileName = $fileSystem->name($routeFile);
					$group    = ['prefix' => '',];
					if ($fileName == "api") {
						$group['middleware'] = "api";
						$group['prefix']     = "api";
					}
					if ($fileName == "web") {
						$group['middleware'] = 'web';
					}
					Route::group($group, function ($router) use ($routeFile, $fileName, $module) {
						require $routeFile;
						if ($fileName == "web" && $module->isConfigurable()) {
							Route::get('modules/' . strtolower($module->getName()) . '/settings', $module->getSettingsControllerString() . '@index')
								 ->name('modules.' . strtolower($module->getName()) . '.settings')->middleware('auth');
						}
					});
				}
			}
		}
	}

	private function loadTranslations(Module $module)
	{
		$translationsPath = $module->getTranslationsPath();
		$fileSystem       = new Filesystem();
		if ($translationsPath != null) {
			if ($fileSystem->exists($translationsPath)) {
				Lang::addNamespace($module->getName(), $translationsPath);
			}
		}
	}

	public function subscribeEvents(Module $module)
	{
		$this->subscribe[] = $module;
	}
}