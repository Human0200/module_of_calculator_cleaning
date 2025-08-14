<?php
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('NO_AGENT_CHECK', true);
define('PUBLIC_AJAX_MODE', true);
define('DisableEventsCheck', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!check_bitrix_sessid()) {
    echo json_encode(['error' => 'Invalid session']);
    die();
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Iblock\SectionTable;

if (!Loader::includeModule('iblock')) {
    echo json_encode(['error' => 'Модуль iblock не подключен']);
    return;
}

Header('Content-Type: application/json; charset=' . LANG_CHARSET);

$request = Application::getInstance()->getContext()->getRequest();
$action = $request->get('action');
$parentId = (int)$request->get('parent_id');

$catalogIblockId = 14;

function renderDealProductsHtml($dealProducts)
{
?>
    <?php if (!empty($dealProducts)): ?>
        <div class="main-grid-container">
            <table class="main-grid-table">
                <thead class="main-grid-header">
                    <tr class="main-grid-row-head">
                        <th class="main-grid-cell-head">
                            <span class="main-grid-cell-content">Наименование</span>
                        </th>
                        <th class="main-grid-cell-head">
                            <span class="main-grid-cell-content">Количество</span>
                        </th>
                        <th class="main-grid-cell-head">
                            <span class="main-grid-cell-content">Цена</span>
                        </th>
                        <th class="main-grid-cell-head">
                            <span class="main-grid-cell-content">Сумма</span>
                        </th>
                        <th class="main-grid-cell-head">
                            <span class="main-grid-cell-content">Действия</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="main-grid-body">
                    <?php foreach ($dealProducts as $product): ?>
                        <tr class="main-grid-row">
                            <td class="main-grid-cell">
                                <span class="main-grid-cell-content"><?= htmlspecialchars($product['NAME']) ?></span>
                            </td>
                            <td class="main-grid-cell">
                                <span class="main-grid-cell-content"><?= $product['QUANTITY'] ?></span>
                            </td>
                            <td class="main-grid-cell">
                                <span class="main-grid-cell-content"><?= number_format($product['PRICE'], 2, ',', ' ') ?> ₽</span>
                            </td>
                            <td class="main-grid-cell">
                                <span class="main-grid-cell-content"><?= number_format($product['SUM'], 2, ',', ' ') ?> ₽</span>
                            </td>
                            <td class="main-grid-cell">
                                <span class="main-grid-cell-content">
                                    <button class="ui-btn ui-btn-xs ui-btn-primary copy-product-btn"
                                        data-product-id="<?= $product['PRODUCT_ID'] ?>"
                                        data-product-name="<?= htmlspecialchars($product['NAME']) ?>"
                                        data-product-price="<?= $product['PRICE'] ?>"
                                        data-product-quantity="<?= $product['QUANTITY'] ?>"
                                        data-product-unit="<?= $product['MEASURE_NAME'] ?>"
                                        title="Скопировать товар в калькулятор">
                                        Скопировать
                                    </button>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="crm-entity-widget-content-block-field-info">
            <span>Товары по сделке не найдены</span>
        </div>
    <?php endif; ?>
<?php
}

// Логирование для отладки
error_log("Calculator AJAX: action = $action, parent_id = $parentId");

switch ($action) {
    case 'get_services':
        $services = [];
        if ($parentId > 0) {
            try {
                // Используем старый API для надежности
                $res = CIBlockSection::GetList(
                    ['SORT' => 'ASC', 'NAME' => 'ASC'],
                    [
                        'IBLOCK_ID' => $catalogIblockId,
                        'SECTION_ID' => $parentId, // Используем SECTION_ID вместо IBLOCK_SECTION_ID
                        'ACTIVE' => 'Y'
                    ],
                    false,
                    ['ID', 'NAME', 'CODE']
                );

                while ($section = $res->Fetch()) {
                    $services[] = [
                        'ID' => $section['ID'],
                        'NAME' => $section['NAME'],
                        'CODE' => $section['CODE']
                    ];
                }

                error_log("Calculator AJAX: found " . count($services) . " services for parent $parentId");
            } catch (Exception $e) {
                error_log("Calculator AJAX error: " . $e->getMessage());
                echo json_encode(['error' => $e->getMessage()]);
                return;
            }
        }
        echo json_encode($services);
        break;

    case 'duplicate_product_in_deal':
        $result = ['success' => false, 'message' => ''];

        // Получаем параметры
        $dealId = (int)$request->get('deal_id');
        $productId = (int)$request->get('product_id');
        $productName = $request->get('product_name');
        $price = (float)$request->get('price');
        $quantity = (float)$request->get('quantity');
        $unit = $request->get('unit') ?? 'шт';

        // Проверка обязательных полей
        if ($dealId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указан ID сделки']);
            return;
        }

        if ($price <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указана цена товара']);
            return;
        }

        if ($quantity <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указано количество товара']);
            return;
        }

        try {
            // Подключаем необходимые модули
            if (!Loader::includeModule("crm")) {
                throw new Exception('Не удалось подключить модуль CRM');
            }

            // Проверяем существование сделки
            $dealCheck = \Bitrix\Crm\DealTable::getById($dealId)->fetch();
            if (!$dealCheck) {
                throw new Exception('Сделка не найдена');
            }

            // Определяем код единицы измерения
            $measureCode = 796; // По умолчанию "штука"
            $measureName = $unit;

            switch (strtolower($unit)) {
                case 'm2':
                case 'м²':
                    $measureCode = 055; // Квадратный метр
                    $measureName = 'м²';
                    break;
                case 'm':
                case 'м':
                    $measureCode = 006; // Метр
                    $measureName = 'м';
                    break;
                case 'kg':
                case 'кг':
                    $measureCode = 166; // Килограмм
                    $measureName = 'кг';
                    break;
                case 'l':
                case 'л':
                    $measureCode = 112; // Литр
                    $measureName = 'л';
                    break;
                case 'hour':
                case 'час':
                    $measureCode = 356; // Час
                    $measureName = 'час';
                    break;
                case 'day':
                case 'день':
                    $measureCode = 359; // День
                    $measureName = 'день';
                    break;
                case 'm3':
                case 'м³':
                    $measureCode = 113; // Кубический метр
                    $measureName = 'м³';
                    break;
                case 'pack':
                case 'упак':
                    $measureCode = 778; // Упаковка
                    $measureName = 'упак';
                    break;
                case 'service':
                case 'услуга':
                    $measureCode = 796; // Штука (для услуг)
                    $measureName = 'услуга';
                    break;
                default:
                    $measureCode = 796; // Штука
                    $measureName = 'шт';
                    break;
            }

            // Подготавливаем данные для нового товара
            $productRowFields = [
                'OWNER_ID' => $dealId,
                'OWNER_TYPE' => 'D',
                'PRODUCT_NAME' => $productName,
                'PRICE' => $price,
                'PRICE_EXCLUSIVE' => $price,
                'PRICE_NETTO' => $price,
                'PRICE_BRUTTO' => $price,
                'QUANTITY' => $quantity,
                'MEASURE_CODE' => $measureCode,
                'MEASURE_NAME' => $measureName,
                'CURRENCY_ID' => 'RUB',
                'CUSTOMIZED' => 'Y',
                'SORT' => 100
            ];

            // Добавляем товар в сделку
            $addResult = \Bitrix\Crm\ProductRowTable::add($productRowFields);

            if ($addResult->isSuccess()) {
                $productRowId = $addResult->getId();

                // Пересчитываем общую сумму сделки
                $totalSum = 0;
                $productRows = \Bitrix\Crm\ProductRowTable::getList([
                    'filter' => [
                        'OWNER_ID' => $dealId,
                        'OWNER_TYPE' => 'D'
                    ],
                    'select' => ['PRICE', 'QUANTITY']
                ]);

                while ($row = $productRows->fetch()) {
                    $totalSum += ($row['PRICE'] * $row['QUANTITY']);
                }

                // Обновляем сумму сделки
                $updateResult = \Bitrix\Crm\DealTable::update($dealId, [
                    'OPPORTUNITY' => $totalSum,
                    'CURRENCY_ID' => 'RUB'
                ]);

                if ($updateResult->isSuccess()) {
                    $result = [
                        'success' => true,
                        'message' => "Товар \"{$productName}\" успешно скопирован в сделку",
                        'data' => [
                            'product_row_id' => $productRowId,
                            'product_id' => $productId,
                            'product_name' => $productName . ' (копия)',
                            'quantity' => $quantity,
                            'price' => $price,
                            'total_deal_sum' => $totalSum,
                            'unit' => $measureName
                        ]
                    ];
                } else {
                    $result = [
                        'success' => true,
                        'message' => "Товар скопирован, но не удалось обновить сумму сделки: " .
                            implode(', ', $updateResult->getErrorMessages())
                    ];
                }
            } else {
                $result['message'] = 'Ошибка при дублировании товара: ' .
                    implode(', ', $addResult->getErrorMessages());
            }
        } catch (Exception $e) {
            $result['message'] = 'Ошибка: ' . $e->getMessage();
            error_log("Duplicate product error: " . $e->getMessage());
        }

        echo json_encode($result);
        break;

    case 'get_work_types':
        $workTypes = [];
        if ($parentId > 0) {
            try {
                $res = CIBlockSection::GetList(
                    ['SORT' => 'ASC', 'NAME' => 'ASC'],
                    [
                        'IBLOCK_ID' => $catalogIblockId,
                        'SECTION_ID' => $parentId,
                        'ACTIVE' => 'Y'
                    ],
                    false,
                    ['ID', 'NAME', 'CODE']
                );

                while ($section = $res->Fetch()) {
                    $workTypes[] = [
                        'ID' => $section['ID'],
                        'NAME' => $section['NAME'],
                        'CODE' => $section['CODE']
                    ];
                }
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
                return;
            }
        }
        echo json_encode($workTypes);
        break;

    case 'get_work_details':
        $workDetails = [];
        if ($parentId > 0) {
            try {
                if (!Loader::includeModule('iblock')) {
                    throw new \Exception('Module iblock not installed');
                }

                // Получаем ТОВАРЫ (ElementTable), а не подразделы (SectionTable)
                $elements = \Bitrix\Iblock\ElementTable::getList([
                    'select' => ['ID', 'NAME', 'CODE'],
                    'filter' => [
                        'IBLOCK_ID' => $catalogIblockId,
                        'IBLOCK_SECTION_ID' => $parentId, // Фильтр по разделу
                        'ACTIVE' => 'Y'
                    ],
                    'order' => ['SORT' => 'ASC', 'NAME' => 'ASC']
                ]);

                while ($element = $elements->fetch()) {
                    $workDetails[] = [
                        'ID' => $element['ID'],
                        'NAME' => $element['NAME'],
                        'CODE' => $element['CODE']
                    ];
                }
            } catch (\Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
                return;
            }
        }
        echo json_encode($workDetails);
        break;

    case 'get_pollution_degrees':
        $pollutionDegrees = [];
        try {
            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1048);
            if ($factory) {
                $items = $factory->getItems([
                    'filter' => [],
                    'select' => ['ID', 'TITLE'],
                    'order' => ['ID' => 'ASC']
                ]);

                foreach ($items as $item) {
                    $pollutionDegrees[] = [
                        'ID' => $item->getId(),
                        'NAME' => $item->getTitle()
                    ];
                }
            }

            error_log("Calculator AJAX: found " . count($pollutionDegrees) . " pollution degrees");
        } catch (Exception $e) {
            error_log("Calculator AJAX error: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }

        echo json_encode($pollutionDegrees);
        break;

    case 'debug_sections':
        $allSections = [];
        try {
            $res = CIBlockSection::GetList(
                ['LEFT_MARGIN' => 'ASC'],
                [
                    'IBLOCK_ID' => $catalogIblockId,
                    'ACTIVE' => 'Y'
                ],
                false,
                ['ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'DEPTH_LEVEL']
            );

            while ($section = $res->Fetch()) {
                $allSections[] = $section;
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
            return;
        }
        echo json_encode($allSections);
        break;

    // НОВЫЙ CASE для расчета суммы из чек-боксов
    case 'calculate_parameters_sum':
        $pollutionIds = $request->get('parameters_ids');

        // Нормализация входных данных
        if (is_string($pollutionIds)) {
            $pollutionIds = json_decode($pollutionIds, true) ?: explode(',', $pollutionIds);
        }

        // $pollutionIds = array_filter(array_map('intval', (array)$pollutionIds));

        if (empty($pollutionIds)) {
            echo json_encode(['success' => false, 'message' => 'Не переданы ID ПАРАМЕТРОВ: ' . json_encode($pollutionIds)]);
            return;
        }

        try {
            if (!Loader::includeModule('crm')) {
                throw new Exception('Модуль CRM не подключен');
            }

            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1048);
            if (!$factory) {
                throw new Exception('Фабрика не найдена');
            }

            $totalSum = 0;
            $processedItems = [];
            $fieldName = 'UF_CRM_6_1754937588291';

            foreach ($pollutionIds as $id) {
                $item = $factory->getItem($id);
                if (!$item) {
                    // echo json_encode(['success' => false, 'message' => "Item with ID $id not found"]);
                    continue;
                };

                $value = $item->get($fieldName);
                if (is_numeric($value)) {
                    $floatValue = (float)$value;
                    $totalSum += $floatValue;
                    $processedItems[] = [
                        'id' => $id,
                        'title' => $item->getTitle(),
                        'value' => $floatValue
                    ];
                }
            }

            echo json_encode([
                'success' => true,
                'total_sum' => $totalSum,
                'processed_items' => $processedItems,
                'pollution_ids' => $pollutionIds
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        break;

    case 'calculate_work_volume_sum':
        $workVolume = $request->get('work_volume');
        $workUnit = $request->get('work_unit');
        $productId = $request->get('product_id');

        // Нормализация входных данных
        if (!is_numeric($workVolume) || $workVolume <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не передан корректный объем работ: ' . $workVolume]);
            return;
        }

        $workVolume = (float)$workVolume;

        try {
            if (!Loader::includeModule('crm')) {
                throw new Exception('Модуль CRM не подключен');
            }

            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1052);
            if (!$factory) {
                throw new Exception('Фабрика 1052 не найдена');
            }

            // Получаем все элементы фабрики для поиска подходящего диапазона
            $items = $factory->getItems([
                'filter' => [],
                'select' => ['ID', 'TITLE', 'UF_CRM_7_1755151598297', 'UF_CRM_7_1755151607436', 'UF_CRM_7_1754936816197'],
                'order' => ['UF_CRM_7_1755151598297' => 'ASC'] // Сортируем по минимальному значению
            ]);

            $foundItem = null;
            $processedItems = [];

            foreach ($items as $item) {
                $minValue = $item->get('UF_CRM_7_1755151598297'); // Минимальное значение
                $maxValue = $item->get('UF_CRM_7_1755151607436'); // Максимальное значение
                $priceValue = $item->get('UF_CRM_7_1754936816197'); // Цена

                $processedItems[] = [
                    'id' => $item->getId(),
                    'title' => $item->getTitle(),
                    'min_value' => $minValue,
                    'max_value' => $maxValue,
                    'price_value' => $priceValue,
                    'work_volume' => $workVolume,
                    'matches' => ($workVolume >= (float)$minValue && $workVolume <= (float)$maxValue)
                ];

                // Проверяем, попадает ли объем работ в диапазон
                if (
                    is_numeric($minValue) && is_numeric($maxValue) &&
                    $workVolume >= (float)$minValue && $workVolume <= (float)$maxValue
                ) {

                    $foundItem = [
                        'id' => $item->getId(),
                        'title' => $item->getTitle(),
                        'min_value' => (float)$minValue,
                        'max_value' => (float)$maxValue,
                        'unit_price' => is_numeric($priceValue) ? (float)$priceValue : 0,
                        'quantity' => $workVolume,
                        'total_sum' => is_numeric($priceValue) ? (float)$priceValue * $workVolume : 0
                    ];
                    break;
                }
            }

            if ($foundItem) {
                echo json_encode([
                    'success' => true,
                    'total_sum' => $foundItem['total_sum'],
                    'unit_price' => $foundItem['unit_price'],
                    'quantity' => $foundItem['quantity'],
                    'work_unit' => $workUnit,
                    'found_item' => $foundItem,
                    'processed_items' => $processedItems
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Не найден подходящий диапазон для объема работ: $workVolume",
                    'work_volume' => $workVolume,
                    'processed_items' => $processedItems
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        break;

    case 'calculate_pollution_sum':
        $pollutionIds = $request->get('pollution_ids');

        // Нормализация входных данных
        if (is_string($pollutionIds)) {
            $pollutionIds = json_decode($pollutionIds, true) ?: explode(',', $pollutionIds);
        }

        $pollutionIds = array_filter(array_map('intval', (array)$pollutionIds));

        if (empty($pollutionIds)) {
            echo json_encode(['success' => false, 'message' => 'Не переданы ID загрязнений']);
            return;
        }

        try {
            if (!Loader::includeModule('crm')) {
                throw new Exception('Модуль CRM не подключен');
            }

            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1044);
            if (!$factory) {
                throw new Exception('Фабрика не найдена');
            }

            $totalSum = 0;
            $processedItems = [];
            $fieldName = 'UF_CRM_5_1754927912498';

            foreach ($pollutionIds as $id) {
                $item = $factory->getItem((int)$id);
                if (!$item) {
                    error_log("Элемент с ID $id не найден");
                    continue;
                }

                $value = $item->get($fieldName);

                // Преобразование "1,4" в 1.4
                if (is_string($value)) {
                    // Заменяем запятую на точку для корректного преобразования в float
                    $value = str_replace(',', '.', $value);
                }

                $floatValue = (float)$value;

                if ($floatValue > 0) {
                    $totalSum += $floatValue;
                    $processedItems[] = [
                        'id' => $id,
                        'title' => $item->getTitle(),
                        'value' => $floatValue,
                        'original_value' => $item->get($fieldName) // Для отладки
                    ];
                }
            }

            echo json_encode([
                'success' => true,
                'total_sum' => $totalSum,
                'processed_items' => $processedItems,
                'debug_info' => [
                    'field_used' => $fieldName,
                    'input_ids' => $pollutionIds
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        break;

    case 'add_product_to_deal':
        $result = ['success' => false, 'message' => ''];
        $requestData = $request->get('data');

        // Получаем основные параметры
        $dealId = (int)($requestData['deal_id'] ?? $request->get('deal_id'));
        $productId = (int)($requestData['product_id'] ?? $request->get('product_id'));
        $unit = $requestData['unit'] ?? $request->get('unit') ?? 'pcs';

        // Используем $unit вместо $requestData['unit'] в switch
        switch ($unit) {
            case 'm2':
                $quantity = 1;
                $volume_price = (float)($requestData['volume_price'] ?? $request->get('volume_price') ?? 0);
                $volume = (float)($requestData['volume'] ?? $request->get('volume') ?? 0);
                break;
            default:
                $quantity = (float)($requestData['quantity'] ?? $request->get('quantity') ?? 1);
                $volume_price = 0;
                $volume = 0;
                break;
        }
        $price = (float)($requestData['price'] ?? $request->get('price'));
        $coefficient = (float)($requestData['coefficient'] ?? $request->get('coefficient') ?? 1.0);

        // Проверка обязательных полей
        if ($dealId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указан ID сделки']);
            return;
        }

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не выбран товар']);
            return;
        }

        try {
            // Подключаем необходимые модули
            if (!Loader::includeModule("iblock") || !Loader::includeModule("crm") || !Loader::includeModule("catalog")) {
                throw new Exception('Не удалось подключить необходимые модули');
            }

            // Проверяем существование сделки
            $dealCheck = \Bitrix\Crm\DealTable::getById($dealId)->fetch();
            if (!$dealCheck) {
                throw new Exception('Сделка не найдена');
            }

            // Получаем информацию о товаре
            $product = CIBlockElement::GetByID($productId)->Fetch();
            if (!$product) {
                throw new Exception('Товар не найден');
            }

            // Получаем оптимальную цену если не передана
            if ($price <= 0) {
                // Сначала пытаемся получить из каталога
                $priceData = \Bitrix\Catalog\PriceTable::getList([
                    'filter' => ['PRODUCT_ID' => $productId, 'CATALOG_GROUP_ID' => 1],
                    'select' => ['PRICE']
                ])->fetch();

                if ($priceData) {
                    $price = $priceData['PRICE'];
                } else {
                    // Если не найдена, пробуем старый способ
                    $priceData = CCatalogProduct::GetOptimalPrice($productId, 1);
                    $price = $priceData['DISCOUNT_PRICE'] ?? $priceData['PRICE']['PRICE'] ?? 0;
                }
            }

            $sumvolume = $volume * $volume_price;
            $finalPrice = round($price * $coefficient + $sumvolume, 2);

            $productRowFields = [
                'OWNER_ID' => $dealId,
                'OWNER_TYPE' => 'D',
                'PRODUCT_ID' => $productId,
                'PRODUCT_NAME' => $product['NAME'],
                'PRICE' => $finalPrice,
                'PRICE_EXCLUSIVE' => $finalPrice,
                'PRICE_NETTO' => $finalPrice,
                'PRICE_BRUTTO' => $finalPrice,
                'QUANTITY' => $quantity,
                'MEASURE_CODE' => 796, // Код единицы измерения "штука"
                'MEASURE_NAME' => $unit,
                'CURRENCY_ID' => 'RUB',
                'CUSTOMIZED' => 'Y', // Помечаем как измененный вручную
                'SORT' => 100
            ];

            // Добавляем товар в сделку
            $addResult = \Bitrix\Crm\ProductRowTable::add($productRowFields);

            if ($addResult->isSuccess()) {
                $productRowId = $addResult->getId();

                // Пересчитываем общую сумму сделки
                $totalSum = 0;
                $productRows = \Bitrix\Crm\ProductRowTable::getList([
                    'filter' => [
                        'OWNER_ID' => $dealId,
                        'OWNER_TYPE' => 'D'
                    ],
                    'select' => ['PRICE', 'QUANTITY']
                ]);

                while ($row = $productRows->fetch()) {
                    $totalSum += ($row['PRICE'] * $row['QUANTITY']);
                }

                // Обновляем сделку современным способом
                $updateFields = [
                    'OPPORTUNITY' => $totalSum,
                    'CURRENCY_ID' => 'RUB',
                    'UF_CRM_DEAL_CALCULATION_DATA' => json_encode([
                        'coefficient' => $coefficient,
                        'base_price' => $price,
                        'final_price' => $finalPrice
                    ])
                ];

                $updateResult = \Bitrix\Crm\DealTable::update($dealId, $updateFields);

                if ($updateResult->isSuccess()) {
                    $result = [
                        'success' => true,
                        'message' => 'Товар успешно добавлен в сделку',
                        'data' => [
                            'product_row_id' => $productRowId,
                            'product_id' => $productId,
                            'product_name' => $product['NAME'],
                            'quantity' => $quantity,
                            'base_price' => $price,
                            'final_price' => $finalPrice,
                            'coefficient' => $coefficient,
                            'total_deal_sum' => $totalSum,
                            'volume' => $volume,
                            'volume_price' => $volume_price,
                            'sumvolume' => $sumvolume,
                            'unit' => $unit, // Используем $unit вместо $requestData['unit']
                        ]
                    ];
                } else {
                    $result['message'] = 'Товар добавлен, но не удалось обновить сумму сделки: ' .
                        implode(', ', $updateResult->getErrorMessages());
                }
            } else {
                $result['message'] = 'Ошибка при сохранении товара: ' . implode(', ', $addResult->getErrorMessages());
            }
        } catch (Exception $e) {
            $result['message'] = 'Ошибка: ' . $e->getMessage();
            error_log("Add product error: " . $e->getMessage());
        }

        echo json_encode($result);
        break;

    case 'get_product_properties':
        $productId = (int)$request->get('product_id');
        $pollutionPropertyId = 74; // ID свойства для "Степень загрязнения"
        $parametersPropertyId = 71; // ID свойства для "Параметры" - УКАЖИТЕ НУЖНЫЙ ID!
        $iblockId = 14; // ID каталога

        if ($productId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указан ID товара']);
            return;
        }

        try {
            if (!Loader::includeModule('iblock')) {
                throw new Exception('Модуль iblock не подключен');
            }

            // Получаем значения свойства для степеней загрязнения (ID 74)
            $dbPollution = CIBlockElement::GetProperty(
                $iblockId,
                $productId,
                [],
                ['ID' => $pollutionPropertyId]
            );

            $pollutionValues = [];
            while ($prop = $dbPollution->Fetch()) {
                if (!empty($prop['VALUE'])) {
                    $pollutionValues[] = $prop['VALUE'];
                }
            }

            // Получаем значения свойства для параметров (другой ID)
            $dbParameters = CIBlockElement::GetProperty(
                $iblockId,
                $productId,
                [],
                ['ID' => $parametersPropertyId]
            );

            $parametersValues = [];
            while ($param = $dbParameters->Fetch()) {
                if (!empty($param['VALUE'])) {
                    $parametersValues[] = $param['VALUE'];
                }
            }

            error_log("Calculator AJAX: found " . count($pollutionValues) . " pollution values and " . count($parametersValues) . " parameter values for product $productId");
            error_log("Calculator AJAX: pollution values: " . print_r($pollutionValues, true));
            error_log("Calculator AJAX: parameter values: " . print_r($parametersValues, true));

            echo json_encode([
                'success' => true,
                'pollution_properties' => $pollutionValues,
                'parameter_properties' => $parametersValues,
                'product_id' => $productId
            ]);
        } catch (Exception $e) {
            error_log("Calculator get product properties error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
        }
        break;
    case 'get_deal_products':
        $dealId = (int)$request->get('deal_id');

        if ($dealId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Не указан ID сделки']);
            return;
        }

        try {
            if (!Loader::includeModule('crm')) {
                throw new Exception('Модуль CRM не подключен');
            }

            // Получаем товары сделки
            $dealProducts = [];
            $productRows = \Bitrix\Crm\ProductRowTable::getList([
                'filter' => [
                    'OWNER_ID' => $dealId,
                    'OWNER_TYPE' => 'D'
                ],
                'select' => [
                    'ID',
                    'PRODUCT_ID',
                    'PRODUCT_NAME',
                    'QUANTITY',
                    'PRICE',
                    'MEASURE_NAME'
                ],
                'order' => ['ID' => 'DESC'] // Новые товары сверху
            ]);

            while ($row = $productRows->fetch()) {
                $dealProducts[] = [
                    'ID' => $row['ID'],
                    'PRODUCT_ID' => $row['PRODUCT_ID'],
                    'NAME' => $row['PRODUCT_NAME'],
                    'QUANTITY' => $row['QUANTITY'],
                    'PRICE' => $row['PRICE'],
                    'SUM' => $row['PRICE'] * $row['QUANTITY'],
                    'CURRENCY_ID' => $row['CURRENCY_ID'],
                    'MEASURE_NAME' => $row['MEASURE_NAME'] ?: 'шт'
                ];
            }

            // Генерируем HTML для раздела товаров
            ob_start();
            renderDealProductsHtml($dealProducts);
            $html = ob_get_clean();

            echo json_encode([
                'success' => true,
                'html' => $html,
                'products_count' => count($dealProducts),
                'deal_id' => $dealId
            ]);
        } catch (Exception $e) {
            error_log("Get deal products error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка получения товаров: ' . $e->getMessage()
            ]);
        }
        break;

    default:
        echo json_encode(['error' => 'Неизвестное действие: ' . $action]);
}
