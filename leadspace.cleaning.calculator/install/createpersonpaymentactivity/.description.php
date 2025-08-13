<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$arActivityDescription = array(
    "NAME" => GetMessage("CREATEPROCESSACTIVITY_NAME"),
    "DESCRIPTION" => GetMessage("CREATEPROCESSACTIVITY_DESC"),
    "TYPE" => "activity",
    "CLASS" => "CreateProcessActivity",
    "JSCLASS" => "BizProcActivity",
    "CATEGORY" => array(
        "ID" => "other",
    ),
    "PROPERTIES" => array(
        "Recipient" => array(
            "NAME" => GetMessage("CREATEPROCESSACTIVITY_RECIPIENT"),
            "TYPE" => "string",
        ),
        "Subject" => array(
            "NAME" => GetMessage("CREATEPROCESSACTIVITY_SUBJECT"),
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
