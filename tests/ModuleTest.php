<?php

use Gcambon\Modules\Module;

class ModuleTest extends \Tests\TestCase
{

    public function testDefaultConstruct(){

        $aModule = new Module();
        $this->assertEquals('Module', $aModule->getName());
        $this->assertEquals("mod",$aModule->getKey());
    }

    public function testDefaultViewsPath(){
        $aModule = new Module();
        $this->assertEquals("Module", $aModule->getName());
        $this->assertEquals(base_path("modules").DIRECTORY_SEPARATOR.$aModule->getName().DIRECTORY_SEPARATOR."Views",$aModule->getViewsPaths());
    }

    public function testDefaultTranslationsPath(){
        $aModule = new Module();
        $this->assertEquals(base_path("modules").DIRECTORY_SEPARATOR.$aModule->getName().DIRECTORY_SEPARATOR."Langs",$aModule->getTranslationsPath());
    }

    public function testDefaultRoutesPath(){
        $aModule = new Module();
        $this->assertEquals(base_path("modules").DIRECTORY_SEPARATOR.$aModule->getName().DIRECTORY_SEPARATOR."Routes",$aModule->getRoutesPath());
    }

    public function testDefaultDbPRefix(){
        $aModule = new Module();
        $this->assertEquals("",$aModule->getDBPrefix());
    }

    public function testDefaultIsConfigurable(){
        $aModule = new Module();
        $this->assertTrue(!$aModule->isConfigurable());
    }

    public function testDefaultGetKeyg(){
        $aModule = new Module();
        $this->assertEquals("mod", $aModule->getKey());
    }

    public function testDefaultGetClassWithNamespace(){
        $aModule = new Module();
        $this->assertEquals("Gcambon\Modules\Module\Module", $aModule->getClassWithNamespace());
    }

    public function testDefaultGetMigrationPath(){
            $aModule = new Module();
            $this->assertEquals(base_path("modules").DIRECTORY_SEPARATOR.$aModule->getName().DIRECTORY_SEPARATOR."Migrations",$aModule->getMigrationsPath());
    }

    public function testDefaultGetDefaultSeederPath(){
        $aModule = new Module();
        $this->assertEquals(base_path("modules").DIRECTORY_SEPARATOR.$aModule->getName().DIRECTORY_SEPARATOR.'Seeds' . DIRECTORY_SEPARATOR . 'ModuleSeeder',$aModule->getDefaultSeederPath());
    }

    public function getDefaultDbModuleNotActive(){
        $aModule = new Module();
        $this->assertNull($aModule->getDBModule());
    }

    public function getDefaultDbModuleActive(){
        $aModule = new Module();
        $this->assertNull($aModule->getDBModule());
    }

}