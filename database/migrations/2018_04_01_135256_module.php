<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Module extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'modules',
            function( Blueprint $table ) {
                $table->increments( 'id' );
                $table->string( 'key' )
                    ->unique();
                $table->string( 'display_name' );
                $table->string( 'class' );
                $table->boolean( 'installed' )
                    ->default( false );
                $table->integer( 'migration_step' )
                    ->default( - 1 );
                $table->softDeletes();
            } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop( 'modules' );
    }
}
