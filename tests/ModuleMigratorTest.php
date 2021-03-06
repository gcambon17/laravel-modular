<?php

use Gcambon\Modules\Entities\ModuleEntity;
use Gcambon\Modules\ModuleDatabaseMigrationRepository;
use Gcambon\Modules\ModuleMigrator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleMigratorTest extends \Tests\TestCase
{
    private $testMigrationPath;
    private $moduleMigrationPath;
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->testMigrationPath = __DIR__.DIRECTORY_SEPARATOR. 'TestModule' .DIRECTORY_SEPARATOR.'Migrations';
        $this->moduleMigrationPath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations';
    }


    public function testCreate()
    {
        $app = app();
        $repository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrator = new ModuleMigrator($repository, $app['db'], $app['files']);

        $this->assertInstanceOf(ModuleMigrator::class, $migrator);
    }

    public function testMigrate(){
        $app = app();
        $repository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrator = new ModuleMigrator($repository, $app['db'], $app['files']);

        $migrator->run($this->testMigrationPath);

        $effectiveMigrations = $repository->getRan();

        $this->assertEquals('2018_04_01_135256_module',$effectiveMigrations[0]);
        $this->assertEquals('2018_04_05_202412_fortest',$effectiveMigrations[1]);
    }

    public function testRollback(){
        $app = app();
        $repository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrator = new ModuleMigrator($repository, $app['db'], $app['files']);

        $migrator->run($this->testMigrationPath);
        $migrator->rollback($this->testMigrationPath);

        $effectiveMigrations = $repository->getRan();

        $this->assertEquals('2018_04_01_135256_module',$effectiveMigrations[0]);
        $this->assertEquals(1, count($effectiveMigrations));
    }

    public function testRollbackSpecificBatch(){
        $app = app();
        $repository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrator = new ModuleMigrator($repository, $app['db'], $app['files']);

        $migrator->run($this->testMigrationPath);
        $migrator->rollback($this->moduleMigrationPath,['batch'=>1]);

        $effectiveMigrations = $repository->getRan();

        $this->assertEquals('2018_04_05_202412_fortest',$effectiveMigrations[0]);
        $this->assertEquals(1, count($effectiveMigrations));
    }

    public function testRollbackSpecificBatchWithNothingToRollback(){
        $app = app();
        $repository = new ModuleDatabaseMigrationRepository($app['db'], $app['config']['database.migrations']);
        $migrator = new ModuleMigrator($repository, $app['db'], $app['files']);

        $migrator->run($this->testMigrationPath);
        $migrator->rollback($this->moduleMigrationPath,['batch'=>10]);

        $effectiveMigrations = $repository->getRan();

        $this->assertEquals('2018_04_01_135256_module',$effectiveMigrations[0]);
        $this->assertEquals('2018_04_05_202412_fortest',$effectiveMigrations[1]);
        $this->assertEquals(2, count($effectiveMigrations));
        $this->assertEquals('<info>Nothing to rollback.</info>', $migrator->getNotes()[0]);
    }
}