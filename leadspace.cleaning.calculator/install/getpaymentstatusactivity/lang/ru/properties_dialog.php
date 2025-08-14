<?php

$MESS["TBANK_STATUS_ACTIVITY_TITLE"] = "Получение статуса платежа через T-Bank API";

// Основные поля
$MESS["TBANK_STATUS_MAIN_INFO"] = "Параметры запроса";
$MESS["TBANK_STATUS_DOCUMENT_IDS"] = "ID документов платежей";
$MESS["TBANK_STATUS_DOCUMENT_IDS_HINT"] = "Укажите один или несколько ID документов через запятую (например: 3fa85f64-5717-4562-b3fc-2c963f66afa6, 61f656e0-0a86-4ec2-bd43-232499f7ad66)";

// Настройки API
$MESS["TBANK_STATUS_API_SETTINGS"] = "Настройки T-Bank API";
$MESS["TBANK_STATUS_TOKEN"] = "Токен T-Bank API";
$MESS["TBANK_STATUS_TOKEN_HINT"] = "Токен для доступа к T-Bank Business API";
$MESS["TBANK_STATUS_SANDBOX_MODE"] = "Режим тестирования";
$MESS["TBANK_STATUS_SANDBOX_YES"] = "Да (Sandbox)";
$MESS["TBANK_STATUS_SANDBOX_NO"] = "Нет (Production)";
$MESS["TBANK_STATUS_SANDBOX_HINT"] = "В режиме Sandbox используется тестовая среда";

// Справка
$MESS["TBANK_STATUS_HELP_TITLE"] = "Справочная информация:";
$MESS["TBANK_STATUS_HELP_1"] = "ID документов можно получить из результата создания платежа";
$MESS["TBANK_STATUS_HELP_2"] = "Можно проверить статус нескольких платежей одновременно";
$MESS["TBANK_STATUS_HELP_3"] = "Результаты будут разделены по статусам: исполненные, ожидающие, с ошибками";
$MESS["TBANK_STATUS_HELP_4"] = "Используйте режим Sandbox для тестирования";

// Статусы платежей
$MESS["TBANK_STATUS_STATUSES_INFO"] = "Возможные статусы платежей:";

$MESS["TBANK_STATUS_REQUIRED_FIELDS"] = "* - обязательные поля";

// Ошибки валидации
$MESS["TBANK_STATUS_ERROR_DOCUMENT_IDS"] = "Не указаны ID документов для проверки статуса";
$MESS["TBANK_STATUS_ERROR_TOKEN"] = "Не указан токен T-Bank API";
$MESS["TBANK_STATUS_ERROR_INVALID_UUID"] = "Неверный формат ID документа. Должен быть UUID";
$MESS["TBANK_STATUS_ERROR_INVALID_FORMAT"] = "Неверный формат ID документов. Используйте формат: id1,id2,id3";

// Возвращаемые значения
$MESS["TBANK_STATUS_RETURN_INFO"] = "Активити возвращает следующие переменные:";
$MESS["TBANK_STATUS_RETURN_EXECUTED"] = "Статус платежа";

?>