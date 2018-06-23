<?php

use Gcambon\Modules\Module;

class TestModule extends Module
{
    private $moduleDirPath;

    protected $name = 'TestModule';
    protected $key = "mod_test";

    public function __construct()
    {
        $this->moduleDirPath = $this->getModulesDirPath();
    }


    private function getModulesDirPath(): string
    {
        return __DIR__;
    }

    public function getMigrationsPath(): string
    {
        return $this->moduleDirPath . DIRECTORY_SEPARATOR . $this->migrationsPath;
    }
}