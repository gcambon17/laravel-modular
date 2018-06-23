<?php

use Gcambon\Modules\Entities\ModuleEntity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModuleEntityTest extends \Tests\TestCase
{

    use RefreshDatabase;

    public function testCreate()
    {
        $aModule = new ModuleEntity();
        $aModule->key = "key";
        $aModule->display_name = "name";
        $aModule->class = "class";
        $aModule->save();

        $this->assertTrue(is_int($aModule->id));
    }
}