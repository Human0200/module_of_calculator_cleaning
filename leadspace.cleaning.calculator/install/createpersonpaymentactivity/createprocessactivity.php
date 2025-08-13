<?php
use Bitrix\Main\Mail\Mail;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();

class CBPCreatePersonPaymentActivity extends CBPActivity
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->arProperties = [
      "Title" => "",
      "Recipient" => "", 
      "Subject" => "",
      "Message" => ""
    ];
  }

  public function Execute()
  {
    if (!\Bitrix\Main\Loader::includeModule('main')) {
      throw new Exception("Модуль 'main' не установлен.");
    }

    // Получаем ID пользователя из Recipient
    $Id_procces = $this->Recipient;
    $this->WriteToTrackingService("Получен айди процесса: " . $Id_procces);
    if (!$Id_procces) {
      $this->WriteToTrackingService("Все полученные параметры: " . print_r($this->arProperties, true));
      throw new Exception("Получен пустой user_id.");
    }
    $newElementFields = [
    'IBLOCK_ID' => $Id_procces,
    'NAME' => "1111",
    'ACTIVE' => 'Y'
];
    $element = new CIBlockElement;
    $elementId = $element->Add($newElementFields);
	$this->Subject = $elementId;


    return CBPActivityExecutionStatus::Closed;
  }

  public static function ValidateProperties($arTestProperties = [], CBPWorkflowTemplateUser $user = null)
  {
    $errors = [];
    if (empty($arTestProperties["Recipient"])) {
      $errors[] = ["code" => "NotExist", "message" => GetMessage("CREATEPROCESSACTIVITY_ERROR_RECIPIENT")];
    }
    return array_merge($errors, parent::ValidateProperties($arTestProperties, $user));
  }

  public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues, $formName = "")
  {
    $runtime = CBPRuntime::GetRuntime();
    $arMap = ["Recipient" => "recipient", "Subject" => "subject", "Message" => "message"];

    // Если значения ещё не установлены, извлекаем из текущих свойств активности
    if (!is_array($arCurrentValues)) {
      $arCurrentValues = [];
      $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);

      if (is_array($arCurrentActivity["Properties"])) {
        foreach ($arMap as $propertyKey => $fieldName) {
          $arCurrentValues[$fieldName] = $arCurrentActivity["Properties"][$propertyKey] ?? "";
        }
      }
    }

    return $runtime->ExecuteResourceFile(__FILE__, "properties_dialog.php", ["arCurrentValues" => $arCurrentValues]);
  }

  public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
  {
    $arErrors = [];
    $arMap = [
      'Recipient' => 'recipient',
      'Subject' => 'subject',
      'Message' => 'message',
    ];
    $arProperties = [];

    foreach ($arMap as $key => $value) {
      $arProperties[$key] = $arCurrentValues[$value] ?? ""; // Устанавливаем значение по умолчанию, если поле пустое
    }

    $arErrors = self::ValidateProperties($arProperties);
    if (!empty($arErrors)) {
      return false;
    }

    $arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
    $arCurrentActivity["Properties"] = $arProperties;

    return true;
  }
}
