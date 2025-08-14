<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arActivityDescription = array(
    "NAME" => GetMessage("CREATEPAYMENTACTIVITY_NAME"),
    "DESCRIPTION" => GetMessage("CREATEPAYMENTACTIVITY_DESC"),
    "TYPE" => "activity",
    "CLASS" => "CreatePersonPaymentActivity",
    "JSCLASS" => "BizProcActivity",
    "CATEGORY" => array(
        "ID" => "other",
    ),
    "PROPERTIES" => array(
        "RecipientName" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_RECIPIENT_NAME"),
            "TYPE" => "string",
        ),
        "RecipientINN" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_RECIPIENT_INN"),
            "TYPE" => "string",
        ),
        "RecipientAccount" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_RECIPIENT_ACCOUNT"),
            "TYPE" => "string",
        ),
        "RecipientBankBIK" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_RECIPIENT_BANK_BIK"),
            "TYPE" => "string",
        ),
        "Amount" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_AMOUNT"),
            "TYPE" => "double",
        ),
        "PaymentPurpose" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_PAYMENT_PURPOSE"),
            "TYPE" => "string",
        ),
        "PayerAccount" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_PAYER_ACCOUNT"),
            "TYPE" => "string",
        ),
        "TBankToken" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_TBANK_TOKEN"),
            "TYPE" => "string",
        ),
        "IsSandbox" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_IS_SANDBOX"),
            "TYPE" => "bool",
        ),
    ),
    "RETURN" => array(
        "PaymentId" => array(
            "NAME" => "ID созданного платежа (documentId)",
            "TYPE" => "string",
        ),
    ),
);