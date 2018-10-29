<?php

namespace Gcambon\Modules;

interface ModuleInterface
{
    public function __construct();

    //getters

    public function getName(): string;

    public function isConfigurable(): bool;

    public function getClassWithNamespace(): string;

    public function getRoutesPath(): string;

    public function getViewsPath(): string;

    public function getDBPrefix(): string;

    public function getTranslationsPath(): string;

    public function getKey():string;

    public function getMigrationsPath():string;

    public function getDefaultSeederPath(): string;


    //managing methods

    /**
     * To subscribe at any event
     */
    public function subscribe($events):void;

    /**
     * Actions call after module is active
     */
    public function postActiveActions():void;

    /**
     * Actions call before unactive the module
     */
    public function preUnactiveActions():void;

    /**
     * Actions call after module is installed
     */
    public function postInstallActions():void;

    /**
     * Action call before uninstall a module
     */
    public function preUninstallActions():void;
}