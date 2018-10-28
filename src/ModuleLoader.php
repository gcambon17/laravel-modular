<?php
/**
 * Created by PhpStorm.
 * User: gcambon
 * Date: 03/10/18
 * Time: 23:25
 */

namespace Gcambon\Modules;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\Filesystem;

class ModuleLoader
{
	public function getAllModules()
	: Collection
	{
		$result             = new Collection();
		$fileSystem = new Filesystem();
		$moduleDir = config('laravel-modular.modules_directory');
		if($fileSystem->isDirectory($moduleDir)){
       			$modulesDirectories = $fileSystem->directories($moduleDir);
			foreach ($modulesDirectories as $directory) {
				$moduleClassName = '\\Modules\\' . $fileSystem->basename($directory) . '\\' . $fileSystem->basename($directory);
				if (class_exists($moduleClassName)) {
					$module = new $moduleClassName();
					if($module->getKey() != "mod") {
						$result->push($module);
					}
				}
			}
		}

		return $result;
	}

}
