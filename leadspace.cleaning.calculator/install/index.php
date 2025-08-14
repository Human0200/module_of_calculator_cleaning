<?php
defined('B_PROLOG_INCLUDED') || die;
use Bitrix\Crm\AutomatedSolution\Action\Delete;
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
        CopyDirFiles(
            __DIR__ . '/install/local',
            $_SERVER['DOCUMENT_ROOT'] . '/local',
            true,
            true
        );
        
        CopyDirFiles(
            __DIR__ . '/install/createpersonpaymentactivity',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/activities/custom',
            true,
            true
        );
        
        CopyDirFiles(
            __DIR__ . '/install/getpersonpaymentactivity',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/activities/custom',
            true,
            true
        );
        
        return true;
    }

    function UnInstallFiles()
    {
        
        DeleteDirFiles(
            __DIR__ . '/install/local',
            $_SERVER['DOCUMENT_ROOT'] . '/local/ajax'
        );
        
        
        $createActivityPath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/activities/custom/createpersonpaymentactivity';
        if (is_dir($createActivityPath)) {
            DeleteDirFilesEx('/bitrix/activities/custom/createpersonpaymentactivity');
        }
        
        
        $getActivityPath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/activities/custom/getpersonpaymentactivity';
        if (is_dir($getActivityPath)) {
            DeleteDirFilesEx('/bitrix/activities/custom/getpersonpaymentactivity');
        }
        
        
        $customActivitiesPath = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/activities/custom/';
        
        
        $filesToDelete = [
            'createpersonpaymentactivity.php',
            'getpersonpaymentactivity.php',
            
        ];
        
        foreach ($filesToDelete as $file) {
            $filePath = $customActivitiesPath . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return true;
    }
}