<?php

namespace LeadSpace\TBank;

use Bitrix\Main\Web\HttpClient;

class TBankAPIClient
{
    private $baseUrl;
    private $token;
    private $httpClient;

    public function __construct($token, $isSandbox = true)
    {
        $this->token = $token;
        // Правильные URL согласно требованиям
        $this->baseUrl = $isSandbox ?
            'https://business.tbank.ru/openapi/sandbox' :
            'https://business.tbank.ru/openapi';

        // Используем HTTP клиент Битрикса
        $this->httpClient = new HttpClient();
        $this->setupHttpClient();
    }

    private function setupHttpClient()
    {
        // Настраиваем заголовки
        $this->httpClient->setHeader('Authorization', 'Bearer ' . $this->token);
        $this->httpClient->setHeader('Content-Type', 'application/json');
        $this->httpClient->setHeader('Accept', 'application/json');

        // Настройки таймаутов
        $this->httpClient->setTimeout(30);
        $this->httpClient->setStreamTimeout(30);

        // Отключаем проверку SSL для тестирования (в продакшене лучше включить)
        // $this->httpClient->setSSLVerifyPeer(false);
    }

    /**
     * Функция для записи логов в файл
     */
    private function writeLog($message)
    {
        $logFile = __DIR__ . '/tbank_api_debug.txt';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] {$message}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }

    /**
     * 1. Получение выписки по счету (авторизация)
     */
    public function getStatement($accountNumber, $from, $to = null)
    {
        $params = [
            'accountNumber' => $accountNumber,
            'from' => $from
        ];

        if ($to) {
            $params['to'] = $to;
        }

        $url = $this->baseUrl . '/statement?' . \http_build_query($params);

        $response = $this->httpClient->get($url);
        $httpCode = $this->httpClient->getStatus();

        $error = $this->httpClient->getError();
        if (!empty($error)) {
            throw new \Exception('HTTP Error: ' . \implode(', ', $error));
        }

        return [
            'http_code' => $httpCode,
            'data' => \json_decode($response, true),
            'raw_response' => $response
        ];
    }

    /**
     * 2. Создание платежа
     */
    public function createPayment($paymentData)
    {
        // ПРАВИЛЬНЫЙ URL - точно как вы указали
        $url = $this->baseUrl . '/api/v1/payment/create';

        // Логируем исходные данные для отладки
        // $this->writeLog('=== T-Bank API Request Debug ===');
        // $this->writeLog('Original payment data: ' . json_encode($paymentData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));


        $documentNumber = preg_replace('/[^0-9]/', '', $paymentData['documentNumber'] ?? '');
        if (empty($documentNumber)) {
            $documentNumber = rand(100000, 999999);
        }
        $documentNumber = substr($documentNumber, 0, 6); // Максимум 6 цифр

        // Возвращаемся к оригинальной структуре данных
        $requiredData = [
            // Основные поля платежа
            'documentNumber' => $documentNumber, // Только цифры, до 6 символов
            'date' => $paymentData['date'] ?? date('c'),
            'amount' => (float)($paymentData['amount'] ?? 0),
            'recipientName' => $paymentData['recipientName'] ?? '',
            'inn' => $paymentData['inn'] ?? '',
            'kpp' => $paymentData['kpp'] ?? '0',
            'bankAcnt' => $paymentData['bankAcnt'] ?? '',
            'bankBik' => $paymentData['bankBik'] ?? '',
            'accountNumber' => $paymentData['accountNumber'] ?? '',
            'paymentPurpose' => $paymentData['paymentPurpose'] ?? '',
            'executionOrder' => (int)($paymentData['executionOrder'] ?? 5),
            'uin' => $paymentData['uin'] ?? '0',

            // ОБЯЗАТЕЛЬНЫЕ налоговые поля (для небюджетных платежей передаем "0")
            'taxPayerStatus' => $paymentData['taxPayerStatus'] ?? '0',
            'kbk' => $paymentData['kbk'] ?? '0',
            'oktmo' => $paymentData['oktmo'] ?? '0',
            'taxEvidence' => $paymentData['taxEvidence'] ?? '0',
            'taxPeriod' => $paymentData['taxPeriod'] ?? '0',
            'taxDocNumber' => $paymentData['taxDocNumber'] ?? '0',
            'taxDocDate' => $paymentData['taxDocDate'] ?? '0'
        ];

        // Дополнительные поля для платежей физ. лицам
        if (isset($paymentData['revenueTypeCode']) && !empty($paymentData['revenueTypeCode'])) {
            $requiredData['revenueTypeCode'] = $paymentData['revenueTypeCode'];
        }
        if (isset($paymentData['collectionAmountNumber']) && $paymentData['collectionAmountNumber'] !== null) {
            $requiredData['collectionAmountNumber'] = (float)$paymentData['collectionAmountNumber'];
        }
        if (isset($paymentData['recipientCorrAccountNumber']) && !empty($paymentData['recipientCorrAccountNumber'])) {
            $requiredData['recipientCorrAccountNumber'] = $paymentData['recipientCorrAccountNumber'];
        }

        // Логируем итоговые данные
        // $this->writeLog('Final payment data with required fields: ' . json_encode($requiredData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        // Проверяем, что основные поля заполнены
        $requiredFields = ['documentNumber', 'amount', 'recipientName', 'inn', 'bankAcnt', 'bankBik', 'accountNumber', 'paymentPurpose'];
        foreach ($requiredFields as $field) {
            if (empty($requiredData[$field])) {
                $this->writeLog("ERROR: Required field '{$field}' is empty");
                throw new \Exception("Required field '{$field}' is empty");
            }
        }

        // Формируем JSON
        $jsonData = json_encode($requiredData, JSON_UNESCAPED_UNICODE);

        // Проверяем корректность JSON
        if ($jsonData === false) {
            $this->writeLog('ERROR: Failed to encode payment data to JSON: ' . json_last_error_msg());
            throw new \Exception('Failed to encode payment data to JSON: ' . json_last_error_msg());
        }

        // Логируем итоговый JSON и детали запроса
        // $this->writeLog('Final JSON data: ' . $jsonData);
        // $this->writeLog('JSON length: ' . strlen($jsonData));
        // $this->writeLog('Request URL: ' . $url);
        // $this->writeLog('Base URL: ' . $this->baseUrl);
        // $this->writeLog('Token (first 10 chars): ' . substr($this->token, 0, 10) . '...');

        // Проверяем доступность cURL
        if (function_exists('\curl_init')) {
            return $this->createPaymentWithCurl($url, $jsonData, $requiredData);
        } else {
            return $this->createPaymentWithHttpClient($url, $jsonData, $requiredData);
        }
    }

    /**
     * Создание платежа через cURL (если доступен)
     */
    private function createPaymentWithCurl($url, $jsonData, $requiredData)
    {
        //$this->writeLog('Using cURL for request');

        // Настраиваем заголовки
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'User-Agent: TBankAPIClient/1.0'
        ];

        //$this->writeLog('Request headers: ' . json_encode($headers));

        // Используем cURL напрямую для большего контроля
        $ch = \curl_init();
        \curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true,
        ]);

        $fullResponse = \curl_exec($ch);
        $httpCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = \curl_error($ch);
        $curlInfo = \curl_getinfo($ch);
        $headerSize = \curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        \curl_close($ch);

        // Разделяем заголовки и тело ответа
        $responseHeaders = substr($fullResponse, 0, $headerSize);
        $response = substr($fullResponse, $headerSize);

        // Логируем детали запроса и ответа
        // $this->writeLog('cURL info: ' . json_encode($curlInfo, JSON_PRETTY_PRINT));
        // $this->writeLog('cURL error: ' . $curlError);
        // $this->writeLog('Response headers: ' . $responseHeaders);
        // $this->writeLog('Response body: ' . $response);
        // $this->writeLog('HTTP Code: ' . $httpCode);
        // $this->writeLog('=== End T-Bank API Request Debug ===');

        if (!empty($curlError)) {
            $this->writeLog('ERROR: cURL Error: ' . $curlError);
            throw new \Exception('cURL Error: ' . $curlError);
        }

        // Проверяем HTTP код ответа
        if ($httpCode >= 400) {
            $responseData = json_decode($response, true);
            $errorMessage = isset($responseData['errorMessage']) ?
                $responseData['errorMessage'] : 'HTTP Error: ' . $httpCode;
            $this->writeLog('ERROR: ' . $errorMessage);
            throw new \Exception($errorMessage);
        }

        return [
            'http_code' => $httpCode,
            'data' => json_decode($response, true),
            'raw_response' => $response
        ];
    }

    /**
     * Создание платежа через HttpClient Битрикса (fallback)
     */
    private function createPaymentWithHttpClient($url, $jsonData, $requiredData)
    {
        //$this->writeLog('Using Bitrix HttpClient for request');

        // Переустанавливаем заголовки на всякий случай
        $this->httpClient->setHeader('Authorization', 'Bearer ' . $this->token);
        $this->httpClient->setHeader('Content-Type', 'application/json');
        $this->httpClient->setHeader('Accept', 'application/json');
        $this->httpClient->setHeader('User-Agent', 'TBankAPIClient/1.0');

        $this->writeLog('HttpClient headers being set');

        // Выполняем запрос
        $response = $this->httpClient->post($url, $jsonData);
        $httpCode = $this->httpClient->getStatus();
        $error = $this->httpClient->getError();

        // Логируем детали запроса и ответа
        // $this->writeLog('HttpClient Response: HTTP ' . $httpCode . ', Body: ' . $response);
        // $this->writeLog('HttpClient errors: ' . json_encode($error));
        // $this->writeLog('=== End T-Bank API Request Debug ===');

        if (!empty($error)) {
            $this->writeLog('ERROR: HTTP Error: ' . implode(', ', $error));
            throw new \Exception('HTTP Error: ' . implode(', ', $error));
        }

        // Проверяем HTTP код ответа
        if ($httpCode >= 400) {
            $responseData = json_decode($response, true);
            $errorMessage = isset($responseData['errorMessage']) ?
                $responseData['errorMessage'] : 'HTTP Error: ' . $httpCode;
            $this->writeLog('ERROR: ' . $errorMessage);
            $this->writeLog('Required data: ' . json_encode($response));
            throw new \Exception($errorMessage);
        }

        return [
            'http_code' => $httpCode,
            'data' => json_decode($response, true),
            'raw_response' => $response
        ];
    }

    /**
     * 3. Получение статуса платежа
     */
    public function getPaymentStatus($documentIds)
    {
        // URL для получения статуса платежа
        $url = $this->baseUrl . '/api/v1/payment/status';

        // Формируем данные запроса
        if (is_string($documentIds)) {
            // Если передана строка, парсим её
            $documentIds = array_filter(array_map('trim', explode(',', $documentIds)));
        }

        if (!is_array($documentIds) || empty($documentIds)) {
            throw new \Exception('Document IDs must be a non-empty array or comma-separated string');
        }

        $requestData = [
            'documentIds' => $documentIds
        ];

        // Логируем запрос
        $this->writeLog('=== T-Bank Payment Status Request ===');
        $this->writeLog('Request URL: ' . $url);
        $this->writeLog('Document IDs: ' . json_encode($documentIds, JSON_UNESCAPED_UNICODE));

        // Формируем JSON
        $jsonData = json_encode($requestData, JSON_UNESCAPED_UNICODE);

        if ($jsonData === false) {
            $this->writeLog('ERROR: Failed to encode request data to JSON: ' . json_last_error_msg());
            throw new \Exception('Failed to encode request data to JSON: ' . json_last_error_msg());
        }

        $this->writeLog('Request JSON: ' . $jsonData);

        // Проверяем доступность cURL
        if (function_exists('\curl_init')) {
            return $this->getPaymentStatusWithCurl($url, $jsonData);
        } else {
            return $this->getPaymentStatusWithHttpClient($url, $jsonData);
        }
    }

    /**
     * Получение статуса платежа через cURL
     */
    private function getPaymentStatusWithCurl($url, $jsonData)
    {
        $this->writeLog('Using cURL for payment status request');

        // Настраиваем заголовки
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->token,
            'User-Agent: TBankAPIClient/1.0'
        ];

        // Используем cURL
        $ch = \curl_init();
        \curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true,
        ]);

        $fullResponse = \curl_exec($ch);
        $httpCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = \curl_error($ch);
        $headerSize = \curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        \curl_close($ch);

        // Разделяем заголовки и тело ответа
        $responseHeaders = substr($fullResponse, 0, $headerSize);
        $response = substr($fullResponse, $headerSize);

        // Логируем ответ
        $this->writeLog('Response HTTP Code: ' . $httpCode);
        $this->writeLog('Response Body: ' . $response);
        $this->writeLog('cURL Error: ' . $curlError);
        $this->writeLog('=== End T-Bank Payment Status Request ===');

        if (!empty($curlError)) {
            $this->writeLog('ERROR: cURL Error: ' . $curlError);
            throw new \Exception('cURL Error: ' . $curlError);
        }

        return [
            'http_code' => $httpCode,
            'data' => json_decode($response, true),
            'raw_response' => $response
        ];
    }

    /**
     * Получение статуса платежа через HttpClient Битрикса
     */
    private function getPaymentStatusWithHttpClient($url, $jsonData)
    {
        $this->writeLog('Using Bitrix HttpClient for payment status request');

        // Переустанавливаем заголовки
        $this->httpClient->setHeader('Authorization', 'Bearer ' . $this->token);
        $this->httpClient->setHeader('Content-Type', 'application/json');
        $this->httpClient->setHeader('Accept', 'application/json');
        $this->httpClient->setHeader('User-Agent', 'TBankAPIClient/1.0');

        // Выполняем запрос
        $response = $this->httpClient->post($url, $jsonData);
        $httpCode = $this->httpClient->getStatus();
        $error = $this->httpClient->getError();

        // Логируем ответ
        $this->writeLog('HttpClient Response: HTTP ' . $httpCode . ', Body: ' . $response);
        $this->writeLog('HttpClient errors: ' . json_encode($error));
        $this->writeLog('=== End T-Bank Payment Status Request ===');

        if (!empty($error)) {
            $this->writeLog('ERROR: HTTP Error: ' . implode(', ', $error));
            throw new \Exception('HTTP Error: ' . implode(', ', $error));
        }

        return [
            'http_code' => $httpCode,
            'data' => json_decode($response, true),
            'raw_response' => $response
        ];
    }

    /**
     * Получение информации об ошибках HTTP клиента
     */
    public function getLastError()
    {
        return $this->httpClient->getError();
    }

    /**
     * Получение всех заголовков ответа
     */
    public function getResponseHeaders()
    {
        return $this->httpClient->getHeaders();
    }
}
