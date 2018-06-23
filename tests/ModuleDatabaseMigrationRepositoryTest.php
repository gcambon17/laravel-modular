<?php

use Gcambon\Modules\Entities\ModuleEntity;
use Gcambon\Modules\ModuleDatabaseMigrationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleDatabaseMigrationRepositoryTest extends \Tests\TestCase
{

    use RefreshDatabase;

    public function testCreate()
    {
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $this->assertInstanceOf(ModuleDatabaseMigrationRepository::class, $aRepository);
    }

    public function testGetMigrationOfSpecificBatchWithOneMigration(){
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $lastBatch =$aRepository->getLastBatchNumber();

        $this->assertEquals("2018_04_01_135256_module",$aRepository->getAllMigrationOfBatch($lastBatch)[0]->migration);
    }

    public function testGetMigrationOfSpecificBatchWithTwoMigrations(){
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $aRepository->log('2018_migrationForTest', '2');

        $this->assertEquals("2018_migrationForTest",$aRepository->getAllMigrationOfBatch(2)[0]->migration);
    }

    public function testGetMigrationOfSpecificBatchWitManyMigrations(){
        $app = app();
        $aRepository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $aRepository->log('2018_migrationForTest', '2');
        $aRepository->log('2018_migrationForTest1', '3');
        $aRepository->log('2018_migrationForTest2', '4');

        $this->assertEquals("2018_04_01_135256_module",$aRepository->getAllMigrationOfBatch(1)[0]->migration);
        $this->assertEquals("2018_migrationForTest",$aRepository->getAllMigrationOfBatch(2)[0]->migration);
        $this->assertEquals("2018_migrationForTest1",$aRepository->getAllMigrationOfBatch(3)[0]->migration);
        $this->assertEquals("2018_migrationForTest2",$aRepository->getAllMigrationOfBatch(4)[0]->migration);
    }
}