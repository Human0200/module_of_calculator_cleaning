<?php

use Bitrix\Main\Mail\Mail;
use Bitrix\Main\Loader;
use LeadSpace\TBank\TBankAPIClient;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();

class CBPCreatePersonPaymentActivity extends CBPActivity
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->arProperties = [
      "Title" => "",
      "RecipientName" => "",         // ФИО получателя
      "RecipientINN" => "",          // ИНН получателя
      "RecipientAccount" => "",      // Счет получателя
      "RecipientBankBIK" => "",      // БИК банка получателя
      "Amount" => "",                // Сумма платежа
      "PaymentPurpose" => "",        // Назначение платежа
      "PayerAccount" => "",          // Счет плательщика
      "TBankToken" => "",            // Токен T-Bank API
      "IsSandbox" => "Y",            // Режим sandbox (Y/N)
      "PaymentId" => "",             // Переменная для сохранения ID платежа
      
      // Налоговые поля (обязательные для API)
      "TaxPayerStatus" => "0",       // Статус составителя расчетного документа
      "KBK" => "0",                  // Код бюджетной классификации
      "OKTMO" => "0",                // Код ОКТМО
      "TaxEvidence" => "0",          // Основание налогового платежа
      "TaxPeriod" => "0",            // Налоговый период
      "TaxDocNumber" => "0",         // Номер налогового документа
      "TaxDocDate" => "0",           // Дата налогового документа
      
      // Дополнительные поля для физ. лиц
      "RevenueTypeCode" => "",       // Код вида выплаты (для физ. лиц)
      "CollectionAmountNumber" => "", // Удержанная сумма
      "RecipientCorrAccountNumber" => "", // Корр. счет банка получателя
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

      // Получаем параметры платежа
      $recipientName = trim($this->RecipientName);
      $recipientINN = trim($this->RecipientINN);
      $recipientAccount = trim($this->RecipientAccount);
      $recipientBankBIK = trim($this->RecipientBankBIK);
      $amount = floatval($this->Amount);
      $paymentPurpose = trim($this->PaymentPurpose);
      $payerAccount = trim($this->PayerAccount);
      $tbankToken = trim($this->TBankToken);
      $isSandbox = ($this->IsSandbox === 'Y');

      // Налоговые поля
      $taxPayerStatus = trim($this->TaxPayerStatus) ?: "0";
      $kbk = trim($this->KBK) ?: "0";
      $oktmo = trim($this->OKTMO) ?: "0";
      $taxEvidence = trim($this->TaxEvidence) ?: "0";
      $taxPeriod = trim($this->TaxPeriod) ?: "0";
      $taxDocNumber = trim($this->TaxDocNumber) ?: "0";
      $taxDocDate = trim($this->TaxDocDate) ?: "0";

      // Дополнительные поля
      $revenueTypeCode = trim($this->RevenueTypeCode);
      $collectionAmountNumber = $this->CollectionAmountNumber ? floatval($this->CollectionAmountNumber) : null;
      $recipientCorrAccountNumber = trim($this->RecipientCorrAccountNumber);
     // $this->WriteToTrackingService("Режим sandbox: " . ($isSandbox ? 'Да' : 'Нет'));

      // Валидация обязательных полей
      if (empty($recipientName)) {
        throw new Exception("Не указано ФИО получателя");
      }
      if (empty($recipientINN)) {
        throw new Exception("Не указан ИНН получателя");
      }
      if (empty($recipientAccount)) {
        throw new Exception("Не указан счет получателя");
      }
      if (empty($recipientBankBIK)) {
        throw new Exception("Не указан БИК банка получателя");
      }
      if ($amount <= 0) {
        throw new Exception("Сумма платежа должна быть больше нуля");
      }
      if (empty($paymentPurpose)) {
        throw new Exception("Не указано назначение платежа");
      }
      if (empty($payerAccount)) {
        throw new Exception("Не указан счет плательщика");
      }
      if (empty($tbankToken)) {
        throw new Exception("Не указан токен T-Bank API");
      }

      Loader::includeModule('leadspace.cleaning.calculator');
      
      // Инициализируем клиент T-Bank API
      $client = new TBankAPIClient($tbankToken, $isSandbox);
     // $this->WriteToTrackingService("T-Bank API клиент инициализирован");

      // Формируем данные для платежа
      $paymentData = [
        "documentNumber" => uniqid('PAY_'), // Уникальный номер документа
        "date" => date('c'), // Текущая дата в ISO формате
        "amount" => $amount,
        "recipientName" => $recipientName,
        "inn" => $recipientINN,
        "kpp" => "0", // Для физлиц всегда 0
        "bankAcnt" => $recipientAccount,
        "bankBik" => $recipientBankBIK,
        "accountNumber" => $payerAccount,
        "paymentPurpose" => $paymentPurpose,
        "executionOrder" => 5, // Стандартная очередность
        "uin" => "0", // УИН (если не требуется - 0)
        
        // Обязательные налоговые поля
        "taxPayerStatus" => $taxPayerStatus,
        "kbk" => $kbk,
        "oktmo" => $oktmo,
        "taxEvidence" => $taxEvidence,
        "taxPeriod" => $taxPeriod,
        "taxDocNumber" => $taxDocNumber,
        "taxDocDate" => $taxDocDate,
      ];

      // Добавляем дополнительные поля, если они заполнены
      if (!empty($revenueTypeCode)) {
        $paymentData["revenueTypeCode"] = $revenueTypeCode;
      }
      if ($collectionAmountNumber !== null) {
        $paymentData["collectionAmountNumber"] = $collectionAmountNumber;
      }
      if (!empty($recipientCorrAccountNumber)) {
        $paymentData["recipientCorrAccountNumber"] = $recipientCorrAccountNumber;
      }

    //  $this->WriteToTrackingService("Данные платежа сформированы: " . json_encode($paymentData, JSON_UNESCAPED_UNICODE));

      // Создаем платеж через API
      $result = $client->createPayment($paymentData);

     // $this->WriteToTrackingService("Ответ API - HTTP код: " . $result['http_code']);
     // $this->WriteToTrackingService("Ответ API - данные: " . json_encode($result['data'], JSON_UNESCAPED_UNICODE));

      // Проверяем результат
      if ($result['http_code'] == 200 || $result['http_code'] == 201) {
       // $this->WriteToTrackingService("✓ Платеж успешно создан!");

        // Сохраняем ID платежа из ответа API
        if (isset($result['data']['documentId'])) {
          $this->PaymentId = $result['data']['documentId'];
          $this->WriteToTrackingService("ID созданного платежа (documentId): " . $this->PaymentId);
        } elseif (isset($result['data']['id'])) {
          // Fallback на случай, если API возвращает 'id' вместо 'documentId'
          $this->PaymentId = $result['data']['id'];
          $this->WriteToTrackingService("ID созданного платежа (id): " . $this->PaymentId);
        } else {
          $this->WriteToTrackingService("⚠️ Внимание: ID платежа не найден в ответе API");
          $this->WriteToTrackingService("Структура ответа: " . json_encode($result['data'], JSON_UNESCAPED_UNICODE));
        }

        return CBPActivityExecutionStatus::Closed;
      } else {
        // Обрабатываем ошибки API
        $errorMessage = "Ошибка создания платежа. HTTP код: " . $result['http_code'];

        if (isset($result['data']['error'])) {
          $errorMessage .= ". Ошибка: " . $result['data']['error'];
        }

        if (isset($result['data']['message'])) {
          $errorMessage .= ". Сообщение: " . $result['data']['message'];
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
    $requiredFields = [
      'RecipientName' => 'Не указано ФИО получателя',
      'RecipientINN' => 'Не указан ИНН получателя',
      'RecipientAccount' => 'Не указан счет получателя',
      'RecipientBankBIK' => 'Не указан БИК банка получателя',
      'Amount' => 'Не указана сумма платежа',
      'PaymentPurpose' => 'Не указано назначение платежа',
      'PayerAccount' => 'Не указан счет плательщика',
      'TBankToken' => 'Не указан токен T-Bank API'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
      if (empty($arTestProperties[$field])) {
        $errors[] = ["code" => "NotExist", "message" => $errorMessage];
      }
    }

    return array_merge($errors, parent::ValidateProperties($arTestProperties, $user));
  }

  public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues, $formName = "")
  {
    $runtime = CBPRuntime::GetRuntime();

    $arMap = [
      "RecipientName" => "recipient_name",
      "RecipientINN" => "recipient_inn",
      "RecipientAccount" => "recipient_account",
      "RecipientBankBIK" => "recipient_bank_bik",
      "Amount" => "amount",
      "PaymentPurpose" => "payment_purpose",
      "PayerAccount" => "payer_account",
      "TBankToken" => "tbank_token",
      "IsSandbox" => "is_sandbox",
      
      // Налоговые поля
      "TaxPayerStatus" => "tax_payer_status",
      "KBK" => "kbk",
      "OKTMO" => "oktmo",
      "TaxEvidence" => "tax_evidence",
      "TaxPeriod" => "tax_period",
      "TaxDocNumber" => "tax_doc_number",
      "TaxDocDate" => "tax_doc_date",
      
      // Дополнительные поля
      "RevenueTypeCode" => "revenue_type_code",
      "CollectionAmountNumber" => "collection_amount_number",
      "RecipientCorrAccountNumber" => "recipient_corr_account_number",
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
      'RecipientName' => 'recipient_name',
      'RecipientINN' => 'recipient_inn',
      'RecipientAccount' => 'recipient_account',
      'RecipientBankBIK' => 'recipient_bank_bik',
      'Amount' => 'amount',
      'PaymentPurpose' => 'payment_purpose',
      'PayerAccount' => 'payer_account',
      'TBankToken' => 'tbank_token',
      'IsSandbox' => 'is_sandbox',
      
      // Налоговые поля
      'TaxPayerStatus' => 'tax_payer_status',
      'KBK' => 'kbk',
      'OKTMO' => 'oktmo',
      'TaxEvidence' => 'tax_evidence',
      'TaxPeriod' => 'tax_period',
      'TaxDocNumber' => 'tax_doc_number',
      'TaxDocDate' => 'tax_doc_date',
      
      // Дополнительные поля
      'RevenueTypeCode' => 'revenue_type_code',
      'CollectionAmountNumber' => 'collection_amount_number',
      'RecipientCorrAccountNumber' => 'recipient_corr_account_number',
    ];

    $arProperties = [];
    foreach ($arMap as $key => $value) {
      $arProperties[$key] = $arCurrentValues[$value] ?? "";
    }

    // Устанавливаем значения по умолчанию
    if (empty($arProperties['IsSandbox'])) {
      $arProperties['IsSandbox'] = 'Y';
    }
    
    // Значения по умолчанию для налоговых полей (небюджетный платеж)
    $taxDefaults = ['TaxPayerStatus', 'KBK', 'OKTMO', 'TaxEvidence', 'TaxPeriod', 'TaxDocNumber', 'TaxDocDate'];
    foreach ($taxDefaults as $field) {
      if (empty($arProperties[$field])) {
        $arProperties[$field] = '0';
      }
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