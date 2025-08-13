<?php
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);
define('DisableEventsCheck', true);

$siteID = isset($_REQUEST['site']) ? mb_substr(preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['site']), 0, 2) : '';
if ($siteID !== '') {
    define('SITE_ID', $siteID);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!check_bitrix_sessid()) {
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;

if (!Loader::includeModule('crm') || !Loader::includeModule('iblock')) {
    echo 'Необходимые модули не подключены';
    return;
}

Header('Content-Type: text/html; charset=' . LANG_CHARSET);

$request = Application::getInstance()->getContext()->getRequest();
$componentData = $request->get('PARAMS');

$entityTypeId = 0;
$entityId = 0;

if (is_array($componentData) && isset($componentData['params'])) {
    $entityTypeId = (int)$componentData['params']['ENTITY_TYPE_ID'];
    $entityId = (int)$componentData['params']['ENTITY_ID'];
}

// Получаем разделы каталога товаров (типы объектов)
$objectTypes = [];
$catalogIblockId = 14; // ID инфоблока каталога товаров
$parentSectionId = 16; // ID родительского раздела "Тип объекта - Категория 1го уровня"

// Используем старый API для совместимости
$sections = CIBlockSection::GetList(
    ['SORT' => 'ASC', 'NAME' => 'ASC'],
    [
        'IBLOCK_ID' => $catalogIblockId,
        'SECTION_ID' => $parentSectionId, // Получаем прямых потомков раздела 16
        'ACTIVE' => 'Y'
    ],
    false,
    ['ID', 'NAME', 'CODE']
);

while ($section = $sections->Fetch()) {
    $objectTypes[] = [
        'ID' => $section['ID'],
        'NAME' => $section['NAME'],
        'CODE' => $section['CODE']
    ];
}

// Получаем данные сделки для списка товаров
$dealProducts = [];
if ($entityTypeId == 2 && $entityId > 0) { // DEAL
    $dbProductRows = CCrmProductRow::LoadRows('D', $entityId);
    foreach ($dbProductRows as $productRow) {
        $dealProducts[] = [
            'ID' => $productRow['PRODUCT_ID'],
            'NAME' => $productRow['PRODUCT_NAME'],
            'QUANTITY' => $productRow['QUANTITY'],
            'PRICE' => $productRow['PRICE'],
            'SUM' => $productRow['QUANTITY'] * $productRow['PRICE']
        ];
    }
}

$APPLICATION->SetTitle("Калькулятор");

// Подключаем CSS
?>
<link rel="stylesheet" href="/local/ajax/calculator_tab_styles.css">

<?php
// Подключаем HTML
include($_SERVER['DOCUMENT_ROOT'] . '/local/ajax/calculator_tab_template.php');

// Подключаем JS
?>
<script>
// Передаем данные в JavaScript
window.calculatorData = {
    objectTypes: <?= json_encode($objectTypes, JSON_UNESCAPED_UNICODE) ?>,
    entityId: <?= $entityId ?>,
    entityTypeId: <?= $entityTypeId ?>
};
window.CURRENT_DEAL_ID = <?= $entityId ?>;
console.log('ID текущей сделки:', window.CURRENT_DEAL_ID);
</script>
<script src="/local/ajax/calculator_tab_script.js"></script>