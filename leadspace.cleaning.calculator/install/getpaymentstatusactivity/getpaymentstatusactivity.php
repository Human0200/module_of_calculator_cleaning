<?php

use Bitrix\Main\Mail\Mail;
use Bitrix\Main\Loader;
use LeadSpace\TBank\TBankAPIClient;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();

class CBPGetPaymentStatusActivity extends CBPActivity
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->arProperties = [
      "Title" => "",
      "DocumentId" => "",            // ID документа для проверки статуса
      "TBankToken" => "",            // Токен T-Bank API
      "IsSandbox" => "Y",            // Режим sandbox (Y/N)
      
      // Возвращаемые значения
      "PaymentStatus" => "",         // Статус платежа (EXECUTED, PENDING, ERROR и т.д.)
    ];
  }

  public function Execute()
  {
    try {
      if (!Loader::includeModule('main')) {
        throw new Exception("Модуль 'main' не установлен.");
      }
      if (!\class_exists('\Bitrix\Main\Web\HttpClient')) {
        throw new Exception("Класс HttpClient не найден. Обновите Битрикс до актуальной версии.");
      }

      // Получаем параметры
      $documentId = $this->DocumentId;
      $tbankToken = trim($this->TBankToken);
      $isSandbox = ($this->IsSandbox === 'Y');

     // $this->WriteToTrackingService("Режим sandbox: " . ($isSandbox ? 'Да' : 'Нет'));

      // Валидация обязательных полей
      if (empty($documentId)) {
        throw new Exception("Не указан ID документа для проверки статуса");
      }
      if (empty($tbankToken)) {
        throw new Exception("Не указан токен T-Bank API");
      }

      // Проверяем формат UUID
      if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $documentId)) {
        throw new Exception("ID документа должен быть в формате UUID: " . $documentId);
      }

      //$this->WriteToTrackingService("ID документа для проверки: " . $documentId);

      Loader::includeModule('leadspace.cleaning.calculator');
      
      // Инициализируем клиент T-Bank API
      $client = new TBankAPIClient($tbankToken, $isSandbox);
      //$this->WriteToTrackingService("T-Bank API клиент инициализирован");

      // Получаем статусы платежей
      $result = $client->getPaymentStatus([$documentId]);

      //$this->WriteToTrackingService("Ответ API - HTTP код: " . $result['http_code']);
     // $this->WriteToTrackingService("Ответ API - данные: " . json_encode($result['data'], JSON_UNESCAPED_UNICODE));

      // Проверяем результат
      if ($result['http_code'] == 200) {
       // $this->WriteToTrackingService("✓ Статус платежа получен успешно!");

        $responseData = $result['data'];
        
        // Инициализируем значение по умолчанию
        $this->PaymentStatus = "UNKNOWN";
        
        // Проверяем успешные результаты
        if (isset($responseData['result']) && is_array($responseData['result']) && count($responseData['result']) > 0) {
          $payment = $responseData['result'][0]; // Берем первый элемент
          
          $this->PaymentStatus = $payment['status'];
         $this->WriteToTrackingService("✓ Статус платежа {$documentId}: " . $this->PaymentStatus);
          
          if (isset($payment['comment'])) {
            $this->WriteToTrackingService("Комментарий: " . $payment['comment']);
          }
        }
        
        // Проверяем ошибки
        if (isset($responseData['resultError']) && is_array($responseData['resultError']) && count($responseData['resultError']) > 0) {
          $error = $responseData['resultError'][0]; // Берем первую ошибку
          
          $this->PaymentStatus = 'ERROR';
          $this->WriteToTrackingService("✗ Ошибка для {$documentId}: {$error['errorCode']} - {$error['errorMessage']}");
        }

        // Простая статистика
       // $this->WriteToTrackingService("📊 Результат: Статус = " . $this->PaymentStatus);

        return CBPActivityExecutionStatus::Closed;
      } else {
        // Обрабатываем ошибки API
        $errorMessage = "Ошибка получения статуса платежей. HTTP код: " . $result['http_code'];

        if (isset($result['data']['error'])) {
          $errorMessage .= ". Ошибка: " . $result['data']['error'];
        }

        if (isset($result['data']['errorMessage'])) {
          $errorMessage .= ". Сообщение: " . $result['data']['errorMessage'];
        }

        $this->WriteToTrackingService("✗ " . $errorMessage);
        $this->WriteToTrackingService("Полный ответ API: " . $result['raw_response']);

        throw new Exception($errorMessage);
      }
    } catch (Exception $e) {
      $this->WriteToTrackingService("Ошибка выполнения активити: " . $e->getMessage());
      throw $e;
    }
  }

  public static function ValidateProperties($arTestProperties = [], CBPWorkflowTemplateUser $user = null)
  {
    $errors = [];

    // Проверяем обязательные поля
    if (empty($arTestProperties['DocumentId'])) {
      $errors[] = ["code" => "NotExist", "message" => "Не указан ID документа для проверки статуса"];
    }
    
    if (empty($arTestProperties['TBankToken'])) {
      $errors[] = ["code" => "NotExist", "message" => "Не указан токен T-Bank API"];
    }

    return array_merge($errors, parent::ValidateProperties($arTestProperties, $user));
  }

  public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues, $formName = "")
  {
    $runtime = CBPRuntime::GetRuntime();

    $arMap = [
      "DocumentId" => "document_ids",
      "TBankToken" => "tbank_token",
      "IsSandbox" => "is_sandbox",
    ];

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
      'DocumentId' => 'document_ids',
      'TBankToken' => 'tbank_token',
      'IsSandbox' => 'is_sandbox',
    ];

    $arProperties = [];
    foreach ($arMap as $key => $value) {
      $arProperties[$key] = $arCurrentValues[$value] ?? "";
    }

    // Устанавливаем значения по умолчанию
    if (empty($arProperties['IsSandbox'])) {
      $arProperties['IsSandbox'] = 'Y';
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