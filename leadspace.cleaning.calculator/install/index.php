<?php
defined('B_PROLOG_INCLUDED') || die;

use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

class leadspace_cleaning_calculator extends CModule
{
    const MODULE_ID = 'leadspace.cleaning.calculator';
    var $MODULE_ID = self::MODULE_ID;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('LEADSPACE.MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('LEADSPACE.MODULE_DESC');
        $this->PARTNER_NAME = Loc::getMessage('LEADSPACE.PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('LEADSPACE.PARTNER_URI');
    }

    function DoInstall()
    {
        if (!Loader::includeModule('crm')) {
            $this->strError = 'Модуль CRM не установлен';
            return false;
        }

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
        $this->InstallFiles();
        return true;
    }

    function DoUninstall()
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        return true;
    }

    function InstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandlerCompatible(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            'LeadSpace\Cleaning\UserField\PollutionDegreeField',
            'getUserTypeDescription'
        );

        $eventManager->registerEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            'LeadSpace\Tabs\Calculator\CalculatorTab',
            'onEntityDetailsTabsInitialized'
        );
    }

    function UnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'main',
            'OnUserTypeBuildList',
            $this->MODULE_ID,
            'LeadSpace\Cleaning\UserField\PollutionDegreeField',
            'getUserTypeDescription'
        );

        $eventManager->unRegisterEventHandler(
            'crm',
            'onEntityDetailsTabsInitialized',
            $this->MODULE_ID,
            'LeadSpace\Tabs\Calculator\CalculatorTab',
            'onEntityDetailsTabsInitialized'
        );
    }

    function InstallFiles()
    {
        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }
}
