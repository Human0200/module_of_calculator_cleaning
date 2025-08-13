<?php
// Простой скрипт для проверки структуры каталога
// Сохраните как /local/ajax/debug_catalog.php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) {
    die('Модуль iblock не подключен');
}

$catalogIblockId = 14;
$parentSectionId = 16;

echo "<h3>Отладка структуры каталога</h3>";

// Проверяем существование инфоблока
$iblock = CIBlock::GetByID($catalogIblockId)->Fetch();
if ($iblock) {
    echo "<p><strong>Инфоблок найден:</strong> {$iblock['NAME']} (ID: {$catalogIblockId})</p>";
} else {
    echo "<p><strong>Ошибка:</strong> Инфоблок с ID {$catalogIblockId} не найден!</p>";
}

// Проверяем родительский раздел
$parentSection = CIBlockSection::GetByID($parentSectionId)->Fetch();
if ($parentSection) {
    echo "<p><strong>Родительский раздел найден:</strong> {$parentSection['NAME']} (ID: {$parentSectionId})</p>";
} else {
    echo "<p><strong>Ошибка:</strong> Родительский раздел с ID {$parentSectionId} не найден!</p>";
}

// Получаем все разделы инфоблока
echo "<h4>Все разделы инфоблока:</h4>";
$sections = CIBlockSection::GetList(
    ['LEFT_MARGIN' => 'ASC'],
    [
        'IBLOCK_ID' => $catalogIblockId,
        'ACTIVE' => 'Y'
    ],
    false,
    ['ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL']
);

echo "<ul>";
while ($section = $sections->Fetch()) {
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $section['DEPTH_LEVEL']);
    $parent = $section['IBLOCK_SECTION_ID'] ? "Parent: {$section['IBLOCK_SECTION_ID']}" : "Корень";
    echo "<li>{$indent}ID: {$section['ID']} | {$section['NAME']} | {$parent}</li>";
}
echo "</ul>";

// Получаем дочерние разделы указанного родителя
echo "<h4>Дочерние разделы родителя ID {$parentSectionId}:</h4>";
$childSections = CIBlockSection::GetList(
    ['SORT' => 'ASC'],
    [
        'IBLOCK_ID' => $catalogIblockId,
        'SECTION_ID' => $parentSectionId, // Обратите внимание: SECTION_ID, не IBLOCK_SECTION_ID
        'ACTIVE' => 'Y'
    ],
    false,
    ['ID', 'NAME', 'CODE']
);

echo "<ul>";
while ($childSection = $childSections->Fetch()) {
    echo "<li>ID: {$childSection['ID']} | {$childSection['NAME']} | Код: {$childSection['CODE']}</li>";
}
echo "</ul>";

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');