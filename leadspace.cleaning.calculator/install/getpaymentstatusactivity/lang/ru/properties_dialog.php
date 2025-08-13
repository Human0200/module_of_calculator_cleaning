<?php

$MESS["TBANK_PAYMENT_ACTIVITY_TITLE"] = "Создание платежа физическому лицу через T-Bank API";

// Информация о получателе
$MESS["TBANK_PAYMENT_RECIPIENT_INFO"] = "Информация о получателе";
$MESS["TBANK_PAYMENT_RECIPIENT_NAME"] = "ФИО получателя";
$MESS["TBANK_PAYMENT_RECIPIENT_NAME_HINT"] = "Полное имя получателя (например: Иванов Иван Иванович)";
$MESS["TBANK_PAYMENT_RECIPIENT_INN"] = "ИНН получателя";
$MESS["TBANK_PAYMENT_RECIPIENT_INN_HINT"] = "ИНН физического лица (12 цифр)";
$MESS["TBANK_PAYMENT_RECIPIENT_ACCOUNT"] = "Счет получателя";
$MESS["TBANK_PAYMENT_RECIPIENT_ACCOUNT_HINT"] = "Номер банковского счета получателя (20 цифр)";
$MESS["TBANK_PAYMENT_RECIPIENT_BANK_BIK"] = "БИК банка получателя";
$MESS["TBANK_PAYMENT_RECIPIENT_BANK_BIK_HINT"] = "БИК банка получателя (9 цифр)";

// Информация о платеже
$MESS["TBANK_PAYMENT_INFO"] = "Параметры платежа";
$MESS["TBANK_PAYMENT_AMOUNT"] = "Сумма платежа";
$MESS["TBANK_PAYMENT_AMOUNT_HINT"] = "Сумма в рублях (например: 1000.50)";
$MESS["TBANK_PAYMENT_PURPOSE"] = "Назначение платежа";
$MESS["TBANK_PAYMENT_PURPOSE_HINT"] = "Описание назначения платежа (например: Оплата по договору №123)";
$MESS["TBANK_PAYMENT_PAYER_ACCOUNT"] = "Счет плательщика";
$MESS["TBANK_PAYMENT_PAYER_ACCOUNT_HINT"] = "Ваш расчетный счет в T-Bank";

// Настройки API
$MESS["TBANK_PAYMENT_API_SETTINGS"] = "Настройки T-Bank API";
$MESS["TBANK_PAYMENT_TOKEN"] = "Токен T-Bank API";
$MESS["TBANK_PAYMENT_TOKEN_HINT"] = "Токен для доступа к T-Bank Business API";
$MESS["TBANK_PAYMENT_SANDBOX_MODE"] = "Режим тестирования";
$MESS["TBANK_PAYMENT_SANDBOX_YES"] = "Да (Sandbox)";
$MESS["TBANK_PAYMENT_SANDBOX_NO"] = "Нет (Production)";
$MESS["TBANK_PAYMENT_SANDBOX_HINT"] = "В режиме Sandbox платежи не выполняются реально";

// Справка
$MESS["TBANK_PAYMENT_HELP_TITLE"] = "Справочная информация:";
$MESS["TBANK_PAYMENT_HELP_1"] = "Убедитесь, что у вас есть действующий договор с T-Bank Business";
$MESS["TBANK_PAYMENT_HELP_2"] = "Получите API токен в личном кабинете T-Bank Business";
$MESS["TBANK_PAYMENT_HELP_3"] = "Проверьте корректность реквизитов получателя";
$MESS["TBANK_PAYMENT_HELP_4"] = "Используйте режим Sandbox для тестирования";

$MESS["TBANK_PAYMENT_REQUIRED_FIELDS"] = "* - обязательные поля";

// Ошибки валидации
$MESS["TBANK_PAYMENT_ERROR_RECIPIENT_NAME"] = "Не указано ФИО получателя";
$MESS["TBANK_PAYMENT_ERROR_RECIPIENT_INN"] = "Не указан ИНН получателя";
$MESS["TBANK_PAYMENT_ERROR_RECIPIENT_ACCOUNT"] = "Не указан счет получателя";
$MESS["TBANK_PAYMENT_ERROR_RECIPIENT_BANK_BIK"] = "Не указан БИК банка получателя";
$MESS["TBANK_PAYMENT_ERROR_AMOUNT"] = "Не указана сумма платежа";
$MESS["TBANK_PAYMENT_ERROR_PURPOSE"] = "Не указано назначение платежа";
$MESS["TBANK_PAYMENT_ERROR_PAYER_ACCOUNT"] = "Не указан счет плательщика";
$MESS["TBANK_PAYMENT_ERROR_TOKEN"] = "Не указан токен T-Bank API";
$MESS["TBANK_PAYMENT_ERROR_AMOUNT_INVALID"] = "Сумма платежа должна быть больше нуля";
$MESS["TBANK_PAYMENT_ERROR_INN_INVALID"] = "ИНН физического лица должен состоять из 12 цифр";
$MESS["TBANK_PAYMENT_ERROR_BIK_INVALID"] = "БИК банка должен состоять из 9 цифр";
?>