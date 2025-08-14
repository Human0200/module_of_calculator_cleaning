<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arActivityDescription = array(
    "NAME" => GetMessage("GETPAYMENTSTATUSACTIVITY_NAME"),
    "DESCRIPTION" => GetMessage("GETPAYMENTSTATUSACTIVITY_DESC"),
    "TYPE" => "activity",
    "CLASS" => "CBPGetPaymentStatusActivity",
    "JSCLASS" => "BizProcActivity",
    "CATEGORY" => array(
        "ID" => "other",
    ),
    "PROPERTIES" => array(
        "DocumentId" => array(
            "NAME" => GetMessage("GETPAYMENTSTATUSACTIVITY_DOCUMENT_ID"),
            "TYPE" => "string",
        ),
        "TBankToken" => array(
            "NAME" => GetMessage("GETPAYMENTSTATUSACTIVITY_TOKEN"),
            "TYPE" => "string",
        ),
        "IsSandbox" => array(
            "NAME" => GetMessage("GETPAYMENTSTATUSACTIVITY_SANDBOX"),
            "TYPE" => "bool",
        ),
    ),
    "RETURN" => array(
        "PaymentStatus" => array(
            "NAME" => "Статус платежа",
            "TYPE" => "string",
        ),
    ),
);