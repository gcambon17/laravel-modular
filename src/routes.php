<?php
if (config('laravel-modular.active_routes') == true) {
    Route::get('laravel-modular', 'Gcambon\Modules\ModuleController@index');
    Route::get('laravel-modular/{moduleKey}/activate', 'Gcambon\Modules\ModuleController@activate');
    Route::get('laravel-modular/{moduleKey}/desactivate', 'Gcambon\Modules\ModuleController@desactivate');
    Route::get('laravel-modular/{moduleKey}/install', 'Gcambon\Modules\ModuleController@install');
    Route::get('laravel-modular/{moduleKey}/uninstall', 'Gcambon\Modules\ModuleController@uninstall');
}