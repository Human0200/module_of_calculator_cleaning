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
        "Recipient" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_RECIPIENT"),
            "TYPE" => "string",
        ),
        "Subject" => array(
            "NAME" => GetMessage("CREATEPAYMENTACTIVITY_SUBJECT"),
            "TYPE" => "string",
        ),
    ),
     "RETURN" => array(
        "Subject" => array(
            "NAME" => "ID созданного элемента",
            "TYPE" => "string",
        ),
     ),
);
