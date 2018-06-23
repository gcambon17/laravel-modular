<?php
/**
 * Created by PhpStorm.
 * User: gcambon
 * Date: 01/04/18
 * Time: 15:12
 */

namespace Gcambon\Modules;

use Gcambon\Modules\ModuleInterface;

class Module implements ModuleInterface
{

    private $moduleDirPath;

    protected $name = 'Module';
    protected $key = "mod";
    protected $viewsPath = 'Views';
    protected $routesPath = 'Routes';
    protected $translationsPath = 'Langs';
    protected $migrationsPath = 'Migrations';
    protected $defaultSeeder = 'Seeds' . DIRECTORY_SEPARATOR . 'ModuleSeeder';
    protected $dbPrefix = null;
    protected $configurable = false;
    protected $dbModule = null;

    public function __construct()
    {
        $this->moduleDirPath = $this->getModulesDirPath();
    }

    private function getModulesDirPath(): string
    {
        return base_path('modules') . DIRECTORY_SEPARATOR . $this->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isConfigurable(): bool
    {
        return $this->configurable;
    }

    public function getClassWithNamespace(): string
    {
        return __NAMESPACE__ . '\\' . $this->getName(). '\\' . $this->getName();
    }

    public function getRoutesPath(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->routesPath;
    }

    public function getViewsPaths(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->viewsPath;
    }

    public function getDBPrefix(): string
    {
        $result = "";
        if (!is_null($this->dbPrefix)) {
            $result = $this->dbPrefix;
        }

        return $result;
    }

    public function getTranslationsPath(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->translationsPath;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getMigrationsPath(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->migrationsPath;
    }

    public function getDefaultSeederPath(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->defaultSeeder;
    }

    public function subscribe(): void
    {
        // TODO: Implement subscribe() method.
    }

    public function postActiveActions(): void
    {
        // TODO: Implement postActiveActions() method.
    }

    public function preUnactiveActions(): void
    {
        // TODO: Implement preUnactiveActions() method.
    }

    public function postInstallActions(): void
    {
        // TODO: Implement postInstallActions() method.
    }

    public function preUninstallActions(): void
    {
        // TODO: Implement preUninstallActions() method.
    }


}