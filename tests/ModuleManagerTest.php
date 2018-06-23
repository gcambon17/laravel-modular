<?php

require_once 'TestModuleEmpty/TestModuleEmpty.php';
require_once 'TestModule/TestModule.php';

use Gcambon\Modules\ModuleDatabaseMigrationRepository;
use Gcambon\Modules\ModuleManager;
use Gcambon\Modules\Module;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleManagerTest extends \Tests\TestCase
{

    use RefreshDatabase;

    public function testConstruct(){
        $aModule = new Module();
        $aManager = new ModuleManager($aModule);

        $this->assertInstanceOf(ModuleManager::class, $aManager);
    }

    public function testGetDbModuleError(){
        $aModule = new Module();
        $aManager = new ModuleManager($aModule);

        $this->expectException(Exception::class);
        $this->expectExceptionCode($aManager::$NOT_FOUND_ERROR_CODE);

        $moduleDB = $aManager->getDBModule();
    }

    public function testIsActiveFalse(){
        $aModule = new Module();
        $aManager = new ModuleManager($aModule);

        $this->assertFalse($aManager->isActive());
    }

    public function testActiveAndIsActiveTrue(){
        $aModule = new Module();
        $aManager = new ModuleManager($aModule);

        $this->assertFalse($aManager->isActive());

        $aManager->active();

        $this->assertTrue($aManager->isActive());
    }

    public function testUnactive(){
        $aModule = new Module();
        $aManager = new ModuleManager($aModule);

        $aManager->active();

        $this->assertTrue($aManager->isActive());

        $aManager->unactive();

        $this->assertFalse($aManager->isActive());
    }

    public function testInstallWithoutMigrations(){
        $aModule = new TestModuleEmpty();
        $aManager = new ModuleManager($aModule);
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrations = $aRepository->getRan();

        $this->assertEquals(1, count($migrations));

        $aManager->active();
        $aManager->install();
        $migrations = $aRepository->getRan();

        $this->assertTrue(boolval($aManager->getDBModule()->installed));
        $this->assertEquals(1, count($migrations));

    }

    public function testInstallWithMigrations(){
        $aModule = new TestModule();
        $aManager = new ModuleManager($aModule);
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrations = $aRepository->getRan();
        $this->assertEquals(1, count($migrations));

        $aManager->active();
        $aManager->install();
        $migrations = $aRepository->getRan();


        $this->assertTrue(boolval($aManager->getDBModule()->installed));
        $this->assertEquals(2, count($migrations));
        $this->assertEquals('2018_04_05_202412_fortest', $migrations[1]);

    }
}