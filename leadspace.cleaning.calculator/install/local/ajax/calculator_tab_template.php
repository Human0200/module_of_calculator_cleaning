<div class="crm-entity-section calculator-tab-wrapper">
    <!-- Первый раздел: Параметры калькулятора -->
    <div class="crm-entity-section">
        <div class="crm-entity-section-content">
            <div class="crm-entity-widget-content">
                <div class="crm-entity-widget-content-block">
                    <div class="ui-entity-editor-content-block">
                        <div class="calculator-form-row">
                            <div class="calculator-form-col">
                                <!-- Тип объекта -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Тип объекта</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select" id="object_type">
                                            <option value="">Выберите тип объекта</option>
                                            <?php foreach ($objectTypes as $objectType): ?>
                                                <option value="<?= $objectType['ID'] ?>" data-code="<?= htmlspecialchars($objectType['CODE']) ?>">
                                                    <?= htmlspecialchars($objectType['NAME']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Вид работ -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Вид работ</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select" id="work_type" disabled>
                                            <option value="">Сначала выберите вид услуги</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Чек-бокс -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Чек-бокс</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select"
                                            id="pollution_degree"
                                            multiple
                                            size="5"
                                            disabled
                                            style="height: auto; min-height: 120px; max-height: 200px; overflow-y: auto;">
                                            <?php
                                            // Загружаем элементы из смарт-процесса ID 1044
                                            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1044);
                                            if ($factory) {
                                                $items = $factory->getItems([
                                                    'filter' => [],
                                                    'select' => ['ID', 'TITLE'],
                                                    'order' => ['ID' => 'ASC']
                                                ]);

                                                foreach ($items as $item) {
                                                    echo '<option value="' . $item->getId() . '">' . htmlspecialchars($item->getTitle()) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="ui-entity-editor-field-help">
                                        </div>
                                    </div>
                                </div>

                                <!-- Объем работ -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Объем работ</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <div style="display: flex; gap: 5px;">
                                            <input type="number"
                                                class="ui-ctl-element"
                                                id="work_volume"
                                                placeholder="Количество"
                                                min="0"
                                                step="0.01"
                                                style="flex: 1;">
                                            <select class="ui-ctl-element calculator-select"
                                                id="work_unit"
                                                style="width: 120px;pointer-events: none;">
                                                <option value="">Выберите товар</option>
                                                <option value="m2">м²</option>
                                                <option value="m">м</option>
                                                <option value="pcs">шт</option>
                                                <option value="kg">кг</option>
                                                <option value="l">л</option>
                                                <option value="hour">час</option>
                                                <option value="day">день</option>
                                                <option value="m3">м³</option>
                                                <option value="pack">упак</option>
                                                <option value="service">услуга</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="calculator-form-col">
                                <!-- Вид услуги -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Вид услуги</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select" id="service_type" disabled>
                                            <option value="">Сначала выберите тип объекта</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Детализация работ (товары) -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Детализация работ</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select" id="work_details" disabled>
                                            <option value="">Сначала выберите вид работ</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Параметры -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-title">
                                        <span>Параметры</span>
                                    </div>
                                    <div class="ui-entity-editor-field-content">
                                        <select class="ui-ctl-element calculator-select"
                                            id="parameters"
                                            multiple
                                            size="5"
                                            disabled
                                            style="height: auto; min-height: 120px; max-height: 200px; overflow-y: auto;">
                                            <?php
                                            // Загружаем элементы из смарт-процесса ID 1048 (тот же источник)
                                            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory(1048);
                                            if ($factory) {
                                                $items = $factory->getItems([
                                                    'filter' => [],
                                                    'select' => ['ID', 'TITLE'],
                                                    'order' => ['ID' => 'ASC']
                                                ]);

                                                foreach ($items as $item) {
                                                    echo '<option value="' . $item->getId() . '">' . htmlspecialchars($item->getTitle()) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="ui-entity-editor-field-help">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Особые пометки и кнопка на всю ширину -->
                        <div class="calculator-form-row">
                            <div class="calculator-form-col-full">

                                <!-- Кнопка расчета -->
                                <div class="ui-entity-editor-field-container">
                                    <div class="ui-entity-editor-field-content">
                                        <button class="ui-btn ui-btn-success" id="calculate_btn">Рассчитать стоимость</button>
                                        <!-- Коэффициент -->
                                        <div class="ui-entity-editor-field-help">
                                            <small style="color: #999;">Коэффициент загрезнения: <span id="coefficient"></span></small>
                                            <br>
                                            <small style="color: #999;">Коэффициент параметров: <span id="coefficient_parameters"></span></small>
                                            </br>
                                            <small style="color: #999;">Р/м2: <span id="additional_cost"></span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Второй раздел: Список товаров по сделке -->
    <div class="crm-entity-section" id="deal_products_section">
        <div class="crm-entity-section-title">
            <span class="crm-entity-section-title-text">Товары по сделке</span>
        </div>
        <div class="crm-entity-section-content">
            <div class="crm-entity-widget-content">
                <div class="crm-entity-widget-content-block">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Результат расчета -->
    <div class="crm-entity-section" id="calculation_result" style="display: none;">
        <div class="crm-entity-section-title">
            <span class="crm-entity-section-title-text">Результат расчета</span>
        </div>
        <div class="crm-entity-section-content">
            <div class="crm-entity-widget-content">
                <div class="crm-entity-widget-content-block">
                    <div id="result_content"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    // Глобальные переменные для хранения всех опций
    let allPollutionOptions = [];
    let allParametersOptions = [];


    // Простая инициализация без сложной логики
    function initCalculator() {
        //   console.log('Инициализация калькулятора');

        const objectTypeSelect = document.getElementById('object_type');
        const serviceTypeSelect = document.getElementById('service_type');
        const workTypeSelect = document.getElementById('work_type');
        const workDetailsSelect = document.getElementById('work_details');
        const workVolumeInput = document.getElementById('work_volume');
        const workUnitSelect = document.getElementById('work_unit');
        const pollutionSelect = document.getElementById('pollution_degree');
        const parametersSelect = document.getElementById('parameters');

        // Сохраняем все опции при инициализации
        saveAllOptions();
        workVolumeInput.addEventListener('input', function() {
            if (workUnitSelect.value === "m2") {
                calculateWorkVolumeSum();
            }
        });

        workUnitSelect.addEventListener('change', function() {
            if (this.value == "m2") {
                calculateWorkVolumeSum();
            } else {
                document.getElementById('additional_cost').innerText = '0.00';
            }
        });

        if (!objectTypeSelect || !serviceTypeSelect || !workTypeSelect || !workDetailsSelect) {
            console.error('Селекты не найдены!');
            return;
        }

        //   console.log('Все селекты найдены, добавляем обработчики');


        // Обработчик кнопки расчета
        const calculateBtn = document.getElementById('calculate_btn');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', function() {

                if (!window.CURRENT_DEAL_ID || window.CURRENT_DEAL_ID <= 0) {
                    alert('Не удалось определить ID сделки');
                    return;
                }

                const dealid = window.CURRENT_DEAL_ID;
                //console.log('ID текущей сделки:', dealid);

                // Получаем выбранные степени загрязнения и параметры
                const pollutionDegreeValues = getPollutionDegreeValues();
                const parametersValues = getParametersValues();
                //console.log('Выбранные степени загрязнения:', pollutionDegreeValues);
                //console.log('Выбранные параметры:', parametersValues);

                // Собираем данные формы
                const formData = {
                    deal_id: dealid,
                    object_type: document.getElementById('object_type').value,
                    service_type: document.getElementById('service_type').value,
                    work_type: document.getElementById('work_type').value,
                    work_details: document.getElementById('work_details').value,
                    pollution_degree: pollutionDegreeValues,
                    parameters: parametersValues,
                    work_volume: document.getElementById('work_volume').value,
                    work_unit: document.getElementById('work_unit').value,
                };

                //console.log('Данные формы:', formData);

                // Валидация
                if (!formData.object_type || !formData.service_type || !formData.work_type) {
                    alert('Пожалуйста, заполните все обязательные поля');
                    return;
                }

                if (!formData.work_details) {
                    alert('Пожалуйста, выберите услугу/товар');
                    return;
                }

                if (!formData.work_volume || formData.work_volume <= 0) {
                    alert('Пожалуйста, укажите объем работ');
                    return;
                }

                //console.log('Отправляем данные на расчет:', formData);
                const coefficient = parseFloat(document.getElementById('coefficient').innerText) + parseFloat(document.getElementById('coefficient_parameters').innerText) || 1.0;
                // console.log('Коэффициент:', coefficient);
                volume_price = parseFloat(document.getElementById('additional_cost').innerText);
                volume = document.getElementById('work_volume').value;
                work_unit = document.getElementById('work_unit').value;
                //  console.log('Объем работ:', volume, 'Единица измерения:', work_unit, 'Цена за единицу:', volume_price);
                // AJAX запрос
                BX.ajax({
                    method: 'POST',
                    url: '/local/ajax/calculator_ajax_handler.php',
                    data: {
                        volume_price: volume_price,
                        volume: volume,
                        coefficient: coefficient,
                        action: 'add_product_to_deal',
                        deal_id: formData.deal_id,
                        product_id: formData.work_details,
                        quantity: formData.work_volume,
                        unit: work_unit,
                        price: 0,
                        object_type: formData.object_type,
                        service_type: formData.service_type,
                        work_type: formData.work_type,
                        pollution_degree: JSON.stringify(formData.pollution_degree),
                        parameters: JSON.stringify(formData.parameters),
                        special_notes: formData.special_notes,
                        sessid: BX.bitrix_sessid()
                    },
                    onsuccess: function(result) {
                        // console.log('Результат расчета:', result);

                        try {
                            const data = JSON.parse(result);
                            if (data.success) {
                                // Успешное добавление товара
                                if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                    BX.UI.Notification.Center.notify({
                                        content: data.message,
                                        position: "top-right",
                                        autoHideDelay: 3000
                                    });
                                } else {
                                    alert(data.message);
                                }

                                // *** ГЛАВНОЕ ИЗМЕНЕНИЕ: Автоматически обновляем таблицу товаров ***
                                setTimeout(() => {
                                    reloadDealProductsSection();
                                }, 1000);

                                // Очищаем форму (опционально)
                                // clearCalculatorForm();

                            } else {
                                // Ошибка
                                alert('Ошибка: ' + data.message);
                            }
                        } catch (e) {
                            console.error('Ошибка парсинга JSON:', e);
                            // console.log('Ответ сервера:', result);
                            alert('Ошибка обработки ответа сервера');
                        }
                    },
                    onfailure: function(error) {
                        console.error('Ошибка AJAX:', error);
                        alert('Ошибка соединения с сервером');
                    }
                });
            });
        }

        // Инициализируем кнопки копирования товаров
        initCopyProductButtons();
    }

    // Обработчик для типа объекта
function setupObjectTypeHandler() {
    const $objectTypeSelect = jQuery('#object_type');
    
    // Удаляем старые обработчики
    $objectTypeSelect.off('change.calculator select2:select.calculator select2:clear.calculator');
    
    // Добавляем новый обработчик для Select2
    $objectTypeSelect.on('change.calculator select2:select.calculator select2:clear.calculator', function() {
        const value = jQuery(this).val();
        console.log('CHANGE EVENT - Тип объекта! Выбрано:', value);

        // Сбрасываем зависимые селекты
        updateSelectOptions('service_type', '<option value="">Сначала выберите тип объекта</option>', true);
        updateSelectOptions('work_type', '<option value="">Сначала выберите вид услуги</option>', true);
        updateSelectOptions('work_details', '<option value="">Сначала выберите вид работ</option>', true);

        // Сбрасываем степени загрязнения и параметры
        resetPollutionDegree();
        resetParameters();
        disablePollutionAndParameters();

        if (value) {
            updateSelectOptions('service_type', '<option value="">Загрузка услуг...</option>', false);

            const xhr = new XMLHttpRequest();
            const url = `/local/ajax/calculator_ajax_handler.php?action=get_services&parent_id=${value}&sessid=<?= bitrix_sessid() ?>`;

            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data && data.length > 0) {
                            let html = '<option value="">Выберите вид услуги</option>';
                            data.forEach(item => {
                                html += `<option value="${item.ID}">${item.NAME}</option>`;
                            });
                            updateSelectOptions('service_type', html, false);
                        } else {
                            updateSelectOptions('service_type', '<option value="">Услуги не найдены</option>', true);
                        }
                    } catch (e) {
                        console.error('Ошибка JSON услуги:', e);
                    }
                }
            };
            xhr.send();
        }
    });
}

// Обработчик для вида услуги
function setupServiceTypeHandler() {
    const $serviceTypeSelect = jQuery('#service_type');
    
    $serviceTypeSelect.off('change.calculator select2:select.calculator select2:clear.calculator');
    
    $serviceTypeSelect.on('change.calculator select2:select.calculator select2:clear.calculator', function() {
        const value = jQuery(this).val();
        console.log('CHANGE EVENT - Вид услуги! Выбрано:', value);

        updateSelectOptions('work_type', '<option value="">Сначала выберите вид услуги</option>', true);
        updateSelectOptions('work_details', '<option value="">Сначала выберите вид работ</option>', true);

        resetPollutionDegree();
        resetParameters();
        disablePollutionAndParameters();

        if (value) {
            updateSelectOptions('work_type', '<option value="">Загрузка видов работ...</option>', false);

            const xhr = new XMLHttpRequest();
            const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_types&parent_id=${value}&sessid=<?= bitrix_sessid() ?>`;

            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data && data.length > 0) {
                            let html = '<option value="">Выберите вид работ</option>';
                            data.forEach(item => {
                                html += `<option value="${item.ID}">${item.NAME}</option>`;
                            });
                            updateSelectOptions('work_type', html, false);
                        } else {
                            updateSelectOptions('work_type', '<option value="">Виды работ не найдены</option>', true);
                        }
                    } catch (e) {
                        console.error('Ошибка JSON виды работ:', e);
                    }
                }
            };
            xhr.send();
        }
    });
}

// Обработчик для вида работ
function setupWorkTypeHandler() {
    const $workTypeSelect = jQuery('#work_type');
    
    $workTypeSelect.off('change.calculator select2:select.calculator select2:clear.calculator');
    
    $workTypeSelect.on('change.calculator select2:select.calculator select2:clear.calculator', function() {
        const value = jQuery(this).val();
        console.log('CHANGE EVENT - Вид работ! Выбрано:', value);

        updateSelectOptions('work_details', '<option value="">Сначала выберите вид работ</option>', true);

        resetPollutionDegree();
        resetParameters();
        disablePollutionAndParameters();

        if (value) {
            updateSelectOptions('work_details', '<option value="">Загрузка товаров...</option>', false);

            const xhr = new XMLHttpRequest();
            const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_details&parent_id=${value}&sessid=<?= bitrix_sessid() ?>`;

            xhr.open('GET', url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data && data.length > 0) {
                            let html = '<option value="">Выберите услугу/товар</option>';
                            data.forEach(item => {
                                html += `<option value="${item.ID}">${item.NAME}</option>`;
                            });
                            updateSelectOptions('work_details', html, false);
                        } else {
                            updateSelectOptions('work_details', '<option value="">Товары не найдены</option>', true);
                        }
                    } catch (e) {
                        console.error('Ошибка JSON товары:', e);
                    }
                }
            };
            xhr.send();
        }
    });
}

// Обработчик для детализации работ
function setupWorkDetailsHandler() {
    const $workDetailsSelect = jQuery('#work_details');
    
    $workDetailsSelect.off('change.calculator select2:select.calculator select2:clear.calculator');
    
    $workDetailsSelect.on('change.calculator select2:select.calculator select2:clear.calculator', function() {
        const value = jQuery(this).val();
        console.log('CHANGE EVENT - Детализация работ! Выбрано:', value);

        resetPollutionDegree();
        resetParameters();

        if (value) {
            enablePollutionAndParameters();
            loadProductPropertiesAndSetSelects(value);
            loadProductMetric(value);
        } else {
            disablePollutionAndParameters();
        }
    });
}

// Вспомогательная функция для обновления селектов
function updateSelectOptions(selectId, html, disabled = false) {
    const select = document.getElementById(selectId);
    const $select = jQuery('#' + selectId);
    
    if (select) {
        select.innerHTML = html;
        select.disabled = disabled;
        
        // Обновляем Select2 если инициализирован
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.trigger('change.select2');
            
            // Включаем/отключаем Select2
            if (disabled) {
                $select.prop('disabled', true).trigger('change.select2');
            } else {
                $select.prop('disabled', false).trigger('change.select2');
            }
        }
    }
}

    function loadProductMetric(productId) {
        //console.log('Загружаем метрику товара:', productId);

        const xhr = new XMLHttpRequest();
        const url = `/local/ajax/calculator_ajax_handler.php?action=get_product_metric&product_id=${productId}&sessid=<?= bitrix_sessid() ?>`;

        //console.log('AJAX URL для метрики товара:', url);

        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('AJAX ответ метрика товара:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        // console.log('Получена метрика товара:', data);

                        if (data.success && data.metric_name) {
                            // Устанавливаем метрику в селект единиц измерения
                            setWorkUnit(data.metric_name);

                            // console.log('Автоматически установлена единица измерения:', data.metric);

                            // Показываем уведомление
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: `Установлена единица измерения: ${data.metric_name || data.metric}`,
                                    position: "top-right",
                                    autoHideDelay: 2000
                                });
                            }
                        } else {
                            // console.log('Метрика товара не найдена или пуста');
                            // Устанавливаем единицу по умолчанию
                            setWorkUnit('pcs'); // штуки по умолчанию
                        }
                    } catch (e) {
                        console.error('Ошибка JSON метрика товара:', e);
                        // В случае ошибки устанавливаем единицу по умолчанию
                        setWorkUnit('pcs');
                    }
                } else {
                    console.error('Ошибка загрузки метрики товара:', xhr.status);
                    // В случае ошибки устанавливаем единицу по умолчанию
                    setWorkUnit('pcs');
                }
            }
        };
        xhr.send();
    }

    function setWorkUnit(metric) {
        // console.log('Устанавливаем единицу измерения:', metric);

        const workUnitSelect = document.getElementById('work_unit');
        if (!workUnitSelect) {
            console.error('Селект единиц измерения не найден!');
            return;
        }

        // Маппинг различных вариантов метрик на значения селекта
        const metricMapping = {
            // Штуки
            'шт': 'pcs',
            'штук': 'pcs',
            'штука': 'pcs',
            'pcs': 'pcs',
            '1': 'pcs', // ID меры "штука"
            '796': 'pcs', // Код единицы измерения "штука"

            // Метры  
            'м': 'm',
            'метр': 'm',
            'метры': 'm',
            'm': 'm',
            '2': 'm', // ID меры "метр"
            '006': 'm', // Код единицы измерения "метр"

            // Сантиметры
            'см': 'm', // Приводим к метрам для упрощения
            'сантиметр': 'm',
            '3': 'm', // ID меры "сантиметр"

            // Миллиметры
            'мм': 'm', // Приводим к метрам для упрощения
            'миллиметр': 'm',
            '4': 'm', // ID меры "миллиметр"

            // Килограммы
            'кг': 'kg',
            'килограмм': 'kg',
            'kg': 'kg',
            '5': 'kg', // ID меры "килограмм"
            '166': 'kg', // Код единицы измерения "килограмм"

            // Граммы
            'г': 'kg', // Приводим к килограммам для упрощения
            'грамм': 'kg',
            '6': 'kg', // ID меры "грамм"

            // Литры
            'л': 'l',
            'литр': 'l',
            'литры': 'l',
            'l': 'l',
            '7': 'l', // ID меры "литр"
            '112': 'l', // Код единицы измерения "литр"

            // Квадратные метры
            'м²': 'm2',
            'кв.м': 'm2',
            'кв м': 'm2',
            'м2': 'm2',
            'm2': 'm2',
            '55': 'm2', // ID меры "квадратный метр"
            '055': 'm2', // Код единицы измерения "квадратный метр"

            // Кубические метры
            'м³': 'm3',
            'куб.м': 'm3',
            'куб м': 'm3',
            'м3': 'm3',
            'm3': 'm3',
            '113': 'm3', // Код и ID единицы измерения "кубический метр"

            // Часы
            'час': 'hour',
            'часы': 'hour',
            'часов': 'hour',
            'hour': 'hour',
            '356': 'hour', // Код единицы измерения "час"

            // Дни
            'день': 'day',
            'дни': 'day',
            'дней': 'day',
            'day': 'day',
            '359': 'day', // Код единицы измерения "день"

            // Упаковки
            'упак': 'pack',
            'упаковка': 'pack',
            'pack': 'pack',
            '778': 'pack', // Код единицы измерения "упаковка"

            // Услуги
            'услуга': 'service',
            'услуги': 'service',
            'service': 'service'
        };

        // Приводим к нижнему регистру для поиска
        const normalizedMetric = String(metric).toLowerCase().trim();
        const mappedUnit = metricMapping[normalizedMetric] || 'pcs'; // По умолчанию штуки

        // console.log(`Маппинг: "${metric}" -> "${normalizedMetric}" -> "${mappedUnit}"`);

        // Устанавливаем значение в селект
        workUnitSelect.value = mappedUnit;

        // Вызываем событие change для обновления зависимых элементов
        workUnitSelect.dispatchEvent(new Event('change'));

        // console.log('Единица измерения установлена:', mappedUnit);
    }

    // Функция для сброса единицы измерения
    function resetWorkUnit() {
        // console.log('Сбрасываем единицу измерения');

        const workUnitSelect = document.getElementById('work_unit');
        if (workUnitSelect) {
            workUnitSelect.value = '';
            workUnitSelect.dispatchEvent(new Event('change'));
        }
    }

    function calculateWorkVolumeSum() {
        const workVolume = document.getElementById('work_volume').value;
        const workUnit = document.getElementById('work_unit').value;
        const workDetails = document.getElementById('work_details').value;

        if (!workVolume || workVolume <= 0) {
            // console.log('Объем работ не указан');
            document.getElementById('additional_cost').innerText = '0.00';
            return;
        }

        // console.log('Ищем цену для объема работ:', workVolume);

        // AJAX запрос для поиска цены
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/local/ajax/calculator_ajax_handler.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        const params = new URLSearchParams();
        params.append('action', 'calculate_work_volume_sum');
        params.append('work_volume', workVolume); // Отправляем введенное значение
        params.append('work_unit', workUnit);
        params.append('product_id', workDetails);
        params.append('sessid', '<?= bitrix_sessid() ?>');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                // console.log('AJAX ответ поиска цены:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // console.log('=== НАЙДЕННАЯ ЦЕНА ===');
                            //  console.log('Цена за единицу:', data.unit_price);
                            //  console.log('Найденный диапазон:', data.found_item);

                            // Показываем найденную цену пользователю
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: `Цена: ${data.unit_price} ₽ за ${data.work_unit}`,
                                    position: "top-right",
                                    autoHideDelay: 3000
                                });
                            }
                            document.getElementById('additional_cost').innerText = data.unit_price;
                            // Выводим цену в интерфейс
                            // displayFoundPrice(data);

                        } else {
                            console.error('Цена не найдена:', data.message);

                            // Показываем что цена не найдена
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: `Цена не найдена: ${data.message}`,
                                    position: "top-right",
                                    autoHideDelay: 4000
                                });
                            }


                        }
                    } catch (e) {
                        console.error('Ошибка парсинга ответа:', e);

                    }
                } else {
                    console.error('Ошибка HTTP:', xhr.status);

                }
            }
        };

        xhr.send(params.toString());
    }


    function reloadDealProductsSection() {
        //console.log('Обновляем раздел товаров по сделке');

        if (!window.CURRENT_DEAL_ID || window.CURRENT_DEAL_ID <= 0) {
            console.error('ID сделки не найден');
            return;
        }

        const dealId = window.CURRENT_DEAL_ID;

        // Находим контейнер с товарами (второй раздел в калькуляторе)
        const productsSections = document.querySelectorAll('.crm-entity-section');
        let productsSection = null;

        productsSections.forEach(section => {
            const title = section.querySelector('.crm-entity-section-title-text');
            if (title && title.textContent.includes('Товары по сделке')) {
                productsSection = section;
            }
        });

        if (!productsSection) {
            console.error('Раздел товаров не найден');
            return;
        }

        const contentContainer = productsSection.querySelector('.crm-entity-widget-content-block');
        if (!contentContainer) {
            console.error('Контейнер содержимого не найден');
            return;
        }

        // Показываем индикатор загрузки
        const originalContent = contentContainer.innerHTML;
        contentContainer.innerHTML = `
        <div style="text-align: center; padding: 20px;">
            <div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #e0e0e0; border-top: 2px solid #007acc; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <div style="margin-top: 10px; color: #666;">Обновление товаров...</div>
        </div>
        <style>
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    `;

        // AJAX запрос для получения обновленного списка товаров
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/local/ajax/calculator_ajax_handler.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        const params = new URLSearchParams();
        params.append('action', 'get_deal_products');
        params.append('deal_id', dealId);
        params.append('sessid', BX.bitrix_sessid());

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                //console.log('AJAX ответ обновления товаров:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // Обновляем содержимое раздела
                            contentContainer.innerHTML = data.html;

                            // Переинициализируем кнопки копирования
                            initCopyProductButtons();

                            // console.log('Раздел товаров успешно обновлен');

                            // Показываем уведомление об успешном обновлении
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: "Список товаров обновлен",
                                    position: "top-right",
                                    autoHideDelay: 2000
                                });
                            }
                        } else {
                            console.error('Ошибка получения товаров:', data.message);
                            contentContainer.innerHTML = originalContent;
                            alert('Ошибка при обновлении товаров: ' + data.message);
                        }
                    } catch (e) {
                        console.error('Ошибка парсинга JSON:', e);
                        contentContainer.innerHTML = originalContent;
                        alert('Ошибка обработки ответа сервера');
                    }
                } else {
                    console.error('Ошибка HTTP:', xhr.status);
                    contentContainer.innerHTML = originalContent;
                    alert('Ошибка соединения с сервером');
                }
            }
        };

        xhr.send(params.toString());
    }


    function modifyCalculateButtonHandler() {
        const calculateBtn = document.getElementById('calculate_btn');
        if (!calculateBtn) return;

        // Находим существующий обработчик и модифицируем его
        calculateBtn.addEventListener('click', function(originalEvent) {
            // Дождемся завершения оригинального обработчика
            setTimeout(() => {
                // Проверяем, был ли успешный результат (можно проверить по появлению уведомления)
                // Обновляем товары через 1 секунду после добавления
                setTimeout(() => {
                    reloadDealProductsSection();
                }, 1000);
            }, 100);
        });
    }

    // Функция сохранения всех опций при инициализации
function saveAllOptions() {
    console.log('Сохраняем все опции селектов');

    const pollutionSelect = document.getElementById('pollution_degree');
    const parametersSelect = document.getElementById('parameters');

    if (pollutionSelect) {
        allPollutionOptions = Array.from(pollutionSelect.options).map(option => ({
            value: option.value,
            text: option.text
        }));
        console.log('Сохранены опции pollution_degree:', allPollutionOptions.length);
    }

    if (parametersSelect) {
        allParametersOptions = Array.from(parametersSelect.options).map(option => ({
            value: option.value,
            text: option.text
        }));
        console.log('Сохранены опции parameters:', allParametersOptions.length);
    }
}

    // Функция включения селектов Чек-бокс и Параметры
function enablePollutionAndParameters() {
    console.log('Включаем селекты Чек-бокс и Параметры');

    const $pollutionSelect = jQuery('#pollution_degree');
    const $parametersSelect = jQuery('#parameters');

    if ($pollutionSelect.length) {
        if ($pollutionSelect.hasClass('select2-hidden-accessible')) {
            $pollutionSelect.prop('disabled', false).trigger('change.select2');
        } else {
            document.getElementById('pollution_degree').disabled = false;
        }
    }

    if ($parametersSelect.length) {
        if ($parametersSelect.hasClass('select2-hidden-accessible')) {
            $parametersSelect.prop('disabled', false).trigger('change.select2');
        } else {
            document.getElementById('parameters').disabled = false;
        }
    }
}

function disablePollutionAndParameters() {
    console.log('Отключаем селекты Чек-бокс и Параметры');

    const $pollutionSelect = jQuery('#pollution_degree');
    const $parametersSelect = jQuery('#parameters');

    if ($pollutionSelect.length) {
        if ($pollutionSelect.hasClass('select2-hidden-accessible')) {
            $pollutionSelect.prop('disabled', true).trigger('change.select2');
        } else {
            document.getElementById('pollution_degree').disabled = true;
        }
    }

    if ($parametersSelect.length) {
        if ($parametersSelect.hasClass('select2-hidden-accessible')) {
            $parametersSelect.prop('disabled', true).trigger('change.select2');
        } else {
            document.getElementById('parameters').disabled = true;
        }
    }
}

    // НОВАЯ ФУНКЦИЯ: Расчет суммы из селекта "Чек-бокс"
    function calculatePollutionSum() {
        const pollutionValues = getPollutionDegreeValues();

        if (pollutionValues.length === 0) {
            // console.log('Степени загрязнения не выбраны');
            document.getElementById('coefficient').innerText = '0.0';
            return;
        }

        // console.log('Рассчитываем сумму для степеней загрязнения:', pollutionValues);

        // AJAX запрос для расчета суммы
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/local/ajax/calculator_ajax_handler.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        const params = new URLSearchParams();
        params.append('action', 'calculate_pollution_sum');
        params.append('pollution_ids', JSON.stringify(pollutionValues));
        params.append('sessid', '<?= bitrix_sessid() ?>');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                // console.log('AJAX ответ расчет суммы загрязнения:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            //    console.log('=== РЕЗУЛЬТАТ РАСЧЕТА СТЕПЕНЕЙ ЗАГРЯЗНЕНИЯ ===');
                            //    console.log('Общая сумма:', data.total_sum);
                            //    console.log('Обработанные элементы:', data.processed_items);
                            //    console.log('Поле:', data.field_name);

                            // Показываем результат пользователю
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: `Коэффициент загрязнения: ${data.total_sum}`,
                                    position: "top-right",
                                    autoHideDelay: 3000
                                });
                                coefficient = document.getElementById('coefficient').innerText = data.total_sum;
                            }
                        } else {
                            console.error('Ошибка расчета:', data.message);
                        }
                    } catch (e) {
                        console.error('Ошибка парсинга JSON расчета:', e);
                    }
                }
            }
        };

        xhr.send(params.toString());
    }

    // НОВАЯ ФУНКЦИЯ: Расчет суммы из селекта "Параметры" (аналогично, если нужно)
    function calculateParametersSum() {
        const parametersValues = getParametersValues();

        if (parametersValues.length === 0) {
            // console.log('Параметры не выбраны');
            document.getElementById('coefficient_parameters').innerText = '0.0';
            return;
        }

        //  console.log('Рассчитываем сумму для параметров:', parametersValues);

        // AJAX запрос для расчета суммы параметров (если нужно)
        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/local/ajax/calculator_ajax_handler.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        const params = new URLSearchParams();
        params.append('action', 'calculate_parameters_sum'); // Нужно добавить этот case в PHP
        params.append('parameters_ids', JSON.stringify(parametersValues));
        params.append('sessid', '<?= bitrix_sessid() ?>');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                //  console.log('AJAX ответ расчет суммы параметров:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // console.log('=== РЕЗУЛЬТАТ РАСЧЕТА ПАРАМЕТРОВ ===');
                            //   console.log('Общая сумма параметров:', data.total_sum);
                            //    console.log('Обработанные элементы:', data.processed_items);

                            // Показываем результат пользователю
                            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                BX.UI.Notification.Center.notify({
                                    content: `Коэффициент параметров: ${data.total_sum}`,
                                    position: "top-right",
                                    autoHideDelay: 3000
                                });
                            }
                            coefficient = document.getElementById('coefficient_parameters').innerText = data.total_sum;
                        } else {
                            console.error('Ошибка расчета параметров:', data.message);
                        }
                    } catch (e) {
                        console.error('Ошибка парсинга JSON расчета параметров:', e);
                    }
                }
            }
        };

        xhr.send(params.toString());
    }

    // Функция скрытия невыбранных опций
    function hideUnselectedOptions(selectId) {
        //  console.log('Скрываем невыбранные опции для:', selectId);

        const select = document.getElementById(selectId);
        if (!select) return;

        const selectedValues = Array.from(select.selectedOptions).map(option => option.value);
        // console.log('Выбранные значения:', selectedValues);

        // Если ничего не выбрано, показываем все опции
        if (selectedValues.length === 0) {
            Array.from(select.options).forEach(option => {
                option.style.display = '';
            });
            return;
        }

        // Скрываем невыбранные опции
        Array.from(select.options).forEach(option => {
            if (selectedValues.includes(option.value)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    }

    // Функция восстановления всех опций
function restoreAllOptions(selectId) {
    console.log('Восстанавливаем все опции для:', selectId);

    const $select = jQuery('#' + selectId);
    
    if ($select.hasClass('select2-hidden-accessible')) {
        // Для Select2: восстанавливаем из сохраненных опций
        const savedOptions = selectId === 'pollution_degree' ? allPollutionOptions : allParametersOptions;
        
        // Очищаем селект
        $select.empty();
        
        // Добавляем все сохраненные опции обратно
        savedOptions.forEach(option => {
            $select.append(new Option(option.text, option.value));
        });
        
        // Обновляем Select2
        $select.trigger('change.select2');
    } else {
        // Для обычного селекта: показываем все опции
        const select = document.getElementById(selectId);
        if (!select) return;

        Array.from(select.options).forEach(option => {
            option.style.display = '';
        });
    }
}

    // Функция загрузки свойств товара и установки степеней загрязнения и параметров
    function loadProductPropertiesAndSetSelects(productId) {
        // console.log('Загружаем свойства товара:', productId);

        const xhr = new XMLHttpRequest();
        const url = `/local/ajax/calculator_ajax_handler.php?action=get_product_properties&product_id=${productId}&sessid=<?= bitrix_sessid() ?>`;

        //('AJAX URL для свойств товара:', url);

        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                //  console.log('AJAX ответ свойства товара:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        // console.log('Получены свойства товара:', data);

                        if (data.success) {
                            // Получаем разные свойства
                            const pollutionProperties = data.pollution_properties || [];
                            const parameterProperties = data.parameter_properties || [];

                            //  console.log('Степени загрязнения:', pollutionProperties);
                            //  console.log('Параметры:', parameterProperties);

                            // Проставляем степени загрязнения
                            if (pollutionProperties.length > 0) {
                                setPollutionDegreeValues(pollutionProperties);
                                // Скрываем невыбранные опции
                                hideUnselectedOptions('pollution_degree');
                                //  console.log('Автоматически проставлены степени загрязнения:', pollutionProperties);
                            }

                            // Проставляем параметры
                            if (parameterProperties.length > 0) {
                                setParametersValues(parameterProperties);
                                // Скрываем невыбранные опции
                                hideUnselectedOptions('parameters');
                                //  console.log('Автоматически проставлены параметры:', parameterProperties);
                            }

                            // Показываем уведомление только если что-то проставилось
                            if (pollutionProperties.length > 0 || parameterProperties.length > 0) {
                                if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                                    BX.UI.Notification.Center.notify({
                                        content: `Автоматически выбраны степени загрязнения и параметры для товара`,
                                        position: "top-right",
                                        autoHideDelay: 2000
                                    });
                                }
                            } else {
                                // console.log('Для данного товара нет автоматических настроек');
                            }
                        }
                    } catch (e) {
                        console.error('Ошибка JSON свойства товара:', e);
                    }
                } else {
                    console.error('Ошибка загрузки свойств товара:', xhr.status);
                }
            }
        };
        xhr.send();
    }

function setPollutionDegreeValues(values) {
    console.log('Показываем только подходящие степени загрязнения:', values);

    const $pollutionSelect = jQuery('#pollution_degree');
    if (!$pollutionSelect.length) {
        console.error('Селект степени загрязнения не найден!');
        return;
    }

    // Сначала показываем все опции
    restoreAllOptions('pollution_degree');

    if ($pollutionSelect.hasClass('select2-hidden-accessible')) {
        // Для Select2: удаляем неподходящие опции из DOM
        $pollutionSelect.find('option').each(function() {
            const optionValue = jQuery(this).val();
            if (optionValue && !values.includes(optionValue)) {
                jQuery(this).remove();
            }
        });
        
        // Обновляем Select2
        $pollutionSelect.trigger('change.select2');
    } else {
        // Для обычного селекта: скрываем неподходящие опции
        Array.from($pollutionSelect[0].options).forEach(option => {
            if (option.value && !values.includes(option.value)) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    }
}

function setParametersValues(values) {
    console.log('Показываем только подходящие параметры:', values);

    const $parametersSelect = jQuery('#parameters');
    if (!$parametersSelect.length) {
        console.error('Селект параметров не найден!');
        return;
    }

    // Сначала показываем все опции
    restoreAllOptions('parameters');

    if ($parametersSelect.hasClass('select2-hidden-accessible')) {
        // Для Select2: удаляем неподходящие опции из DOM
        $parametersSelect.find('option').each(function() {
            const optionValue = jQuery(this).val();
            if (optionValue && !values.includes(optionValue)) {
                jQuery(this).remove();
            }
        });
        
        // Обновляем Select2
        $parametersSelect.trigger('change.select2');
    } else {
        // Для обычного селекта: скрываем неподходящие опции
        Array.from($parametersSelect[0].options).forEach(option => {
            if (option.value && !values.includes(option.value)) {
                option.style.display = 'none';
            } else {
                option.style.display = '';
            }
        });
    }
}

function resetPollutionDegree() {
    console.log('Сбрасываем степени загрязнения');

    const $pollutionSelect = jQuery('#pollution_degree');
    if ($pollutionSelect.length) {
        // Сначала восстанавливаем все опции
        restoreAllOptions('pollution_degree');
        
        // Затем сбрасываем выбор
        if ($pollutionSelect.hasClass('select2-hidden-accessible')) {
            $pollutionSelect.val(null).trigger('change');
        } else {
            Array.from($pollutionSelect[0].options).forEach(option => {
                option.selected = false;
            });
            $pollutionSelect[0].dispatchEvent(new Event('change'));
        }
    }
}

function resetParameters() {
    console.log('Сбрасываем параметры');

    const $parametersSelect = jQuery('#parameters');
    if ($parametersSelect.length) {
        // Сначала восстанавливаем все опции
        restoreAllOptions('parameters');
        
        // Затем сбрасываем выбор
        if ($parametersSelect.hasClass('select2-hidden-accessible')) {
            $parametersSelect.val(null).trigger('change');
        } else {
            Array.from($parametersSelect[0].options).forEach(option => {
                option.selected = false;
            });
            $parametersSelect[0].dispatchEvent(new Event('change'));
        }
    }
}

function getPollutionDegreeValues() {
    const $pollutionSelect = jQuery('#pollution_degree');
    
    if ($pollutionSelect.hasClass('select2-hidden-accessible')) {
        // Получаем через Select2
        return $pollutionSelect.val() || [];
    } else {
        // Обычный способ
        const pollutionSelect = document.getElementById('pollution_degree');
        if (pollutionSelect && pollutionSelect.multiple) {
            return Array.from(pollutionSelect.selectedOptions).map(option => option.value);
        }
    }
    return [];
}

function getParametersValues() {
    const $parametersSelect = jQuery('#parameters');
    
    if ($parametersSelect.hasClass('select2-hidden-accessible')) {
        // Получаем через Select2
        return $parametersSelect.val() || [];
    } else {
        // Обычный способ
        const parametersSelect = document.getElementById('parameters');
        if (parametersSelect && parametersSelect.multiple) {
            return Array.from(parametersSelect.selectedOptions).map(option => option.value);
        }
    }
    return [];
}

    function clearCalculatorForm() {
        // Очищаем форму после успешного добавления
        document.getElementById('work_volume').value = '';
        document.getElementById('special_notes').value = '';
        resetPollutionDegree();
        resetParameters();

        // Отключаем селекты
        disablePollutionAndParameters();

        // Сбрасываем селекты до первоначального состояния
        const workDetailsSelect = document.getElementById('work_details');
        if (workDetailsSelect.options.length > 0) {
            workDetailsSelect.selectedIndex = 0;
        }
    }



    // Функция обновления формы
    function refreshCalculatorForm() {
    if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
        BX.UI.Notification.Center.notify({
            content: "Обновление формы...",
            position: "top-right",
            autoHideDelay: 2000
        });
    }

    try {
        // Сброс основных селектов через Select2 или обычным способом
        const selectsToReset = [
            { id: 'object_type', defaultIndex: 0 },
            { id: 'service_type', html: '<option value="">Сначала выберите тип объекта</option>', disabled: true },
            { id: 'work_type', html: '<option value="">Сначала выберите вид услуги</option>', disabled: true },
            { id: 'work_details', html: '<option value="">Сначала выберите вид работ</option>', disabled: true }
        ];

        selectsToReset.forEach(selectConfig => {
            const $select = jQuery('#' + selectConfig.id);
            const select = document.getElementById(selectConfig.id);
            
            if ($select.hasClass('select2-hidden-accessible')) {
                // Сброс через Select2
                if (selectConfig.defaultIndex !== undefined) {
                    // Для первого селекта - устанавливаем первый элемент
                    $select.prop('selectedIndex', selectConfig.defaultIndex);
                } else {
                    // Для зависимых селектов - меняем содержимое
                    select.innerHTML = selectConfig.html;
                    select.disabled = selectConfig.disabled;
                }
                $select.trigger('change.select2');
                
                if (selectConfig.disabled) {
                    $select.prop('disabled', true).trigger('change.select2');
                }
            } else {
                // Обычный сброс
                if (selectConfig.defaultIndex !== undefined) {
                    select.selectedIndex = selectConfig.defaultIndex;
                } else {
                    select.innerHTML = selectConfig.html;
                    select.disabled = selectConfig.disabled;
                }
            }
        });

        // Сброс единицы измерения
        const workUnitSelect = document.getElementById('work_unit');
        if (workUnitSelect) {
            workUnitSelect.selectedIndex = 0;
        }

        // Очистка поля объема работ
        const workVolumeInput = document.getElementById('work_volume');
        if (workVolumeInput) {
            workVolumeInput.value = '';
        }

        // Сброс множественных селектов
        resetPollutionDegree();
        resetParameters();
        disablePollutionAndParameters();

        // Сброс коэффициентов
        const coefficientElements = ['coefficient', 'coefficient_parameters', 'additional_cost'];
        coefficientElements.forEach(elemId => {
            const elem = document.getElementById(elemId);
            if (elem) {
                elem.innerText = '';
            }
        });

        // Скрытие результата расчета
        const resultSection = document.getElementById('calculation_result');
        if (resultSection) {
            resultSection.style.display = 'none';
            const resultContent = document.getElementById('result_content');
            if (resultContent) {
                resultContent.innerHTML = '';
            }
        }

        setTimeout(() => {
            if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                BX.UI.Notification.Center.notify({
                    content: "Форма обновлена ✓",
                    position: "top-right",
                    autoHideDelay: 2000
                });
            }
        }, 500);

    } catch (error) {
        console.error('Ошибка при обновлении формы:', error);
        
        if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
            BX.UI.Notification.Center.notify({
                content: "Ошибка при обновлении формы",
                position: "top-right",
                autoHideDelay: 3000
            });
        }
    }
}

    function initCopyProductButtons() {
        //  console.log('Инициализируем кнопки копирования товаров');

        const copyButtons = document.querySelectorAll('.copy-product-btn');

        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                //   console.log('Кнопка копирования нажата');

                // Получаем данные из data-атрибутов
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = this.getAttribute('data-product-price');
                const productQuantity = this.getAttribute('data-product-quantity');
                const productUnit = this.getAttribute('data-product-unit');

                // Дублируем товар в сделке напрямую
                duplicateProductInDeal({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                    unit: productUnit
                });
            });
        });
    }

    function addRefreshButtons() {
        const dealProductsSection = document.getElementById('deal_products_section');

        if (!dealProductsSection) {
            console.error('Не найден div с id="deal_products_section"');
            return;
        }

        // Проверяем, не добавлены ли уже кнопки
        if (dealProductsSection.querySelector('.refresh-buttons-container')) {
            return;
        }

        // Создаем контейнер для кнопок
        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = 'refresh-buttons-container';
        buttonsContainer.style.padding = '15px 0';
        buttonsContainer.style.borderBottom = '1px solid #eee';
        buttonsContainer.style.display = 'flex';
        buttonsContainer.style.gap = '10px';
        buttonsContainer.style.alignItems = 'center';

        // Кнопка обновления формы
        const refreshFormBtn = document.createElement('button');
        refreshFormBtn.className = 'ui-btn ui-btn-xs ui-btn-light-border';
        refreshFormBtn.innerHTML = '↻ Обновить форму';
        refreshFormBtn.title = 'Сбросить калькулятор';

        refreshFormBtn.addEventListener('click', function(e) {
            e.preventDefault();
            refreshCalculatorForm();
        });

        // Кнопка обновления товаров
        const refreshProductsBtn = document.createElement('button');
        refreshProductsBtn.className = 'ui-btn ui-btn-xs ui-btn-success';
        refreshProductsBtn.innerHTML = '↻ Обновить товары';
        refreshProductsBtn.title = 'Обновить список товаров';

        refreshProductsBtn.addEventListener('click', function(e) {
            e.preventDefault();
            reloadDealProductsSection();
        });

        // Собираем все вместе
        buttonsContainer.appendChild(refreshFormBtn);
        buttonsContainer.appendChild(refreshProductsBtn);

        // Вставляем в самое начало deal_products_section
        dealProductsSection.insertBefore(buttonsContainer, dealProductsSection.firstChild);
    }

    function duplicateProductInDeal(product) {
        //  console.log('Дублируем товар в сделке:', product);

        if (!window.CURRENT_DEAL_ID || window.CURRENT_DEAL_ID <= 0) {
            alert('Не удалось определить ID сделки');
            return;
        }

        const dealId = window.CURRENT_DEAL_ID;

        // Показываем уведомление о начале процесса
        if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
            BX.UI.Notification.Center.notify({
                content: `Копирование товара "${product.name}"...`,
                position: "top-right",
                autoHideDelay: 2000
            });
        }

        // AJAX запрос для дублирования товара
        BX.ajax({
            method: 'POST',
            url: '/local/ajax/calculator_ajax_handler.php',
            data: {
                action: 'duplicate_product_in_deal',
                deal_id: dealId,
                product_id: product.id,
                product_name: product.name,
                price: product.price,
                quantity: product.quantity,
                unit: product.unit,
                sessid: BX.bitrix_sessid()
            },
            onsuccess: function(result) {
                //  console.log('Результат дублирования:', result);

                try {
                    const data = JSON.parse(result);
                    if (data.success) {
                        // Успешное дублирование товара
                        if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                            BX.UI.Notification.Center.notify({
                                content: data.message,
                                position: "top-right",
                                autoHideDelay: 3000
                            });
                        } else {
                            alert(data.message);
                        }

                        // Автоматически обновляем таблицу товаров
                        setTimeout(() => {
                            reloadDealProductsSection();
                        }, 1000);

                    } else {
                        // Ошибка
                        alert('Ошибка: ' + data.message);
                    }
                } catch (e) {
                    console.error('Ошибка парсинга JSON:', e);
                    // console.log('Ответ сервера:', result);
                    alert('Ошибка обработки ответа сервера');
                }
            },
            onfailure: function(error) {
                console.error('Ошибка AJAX:', error);
                alert('Ошибка соединения с сервером');
            }
        });
    }

    // Функция загрузки Select2
    function loadSelect2() {
        // Проверяем, не загружен ли уже Select2
        if (window.jQuery && window.jQuery.fn.select2) {
            initSelect2Selects();
            return;
        }

        // Загружаем jQuery если его нет
        if (!window.jQuery) {
            const jqueryScript = document.createElement('script');
            jqueryScript.src = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js';
            jqueryScript.onload = function() {
                loadSelect2Resources();
            };
            document.head.appendChild(jqueryScript);
        } else {
            loadSelect2Resources();
        }
    }

    function loadSelect2Resources() {
        // Загружаем CSS Select2
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css';
        document.head.appendChild(link);

        // Загружаем JS Select2
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js';
        script.onload = function() {
            // Даем время на загрузку
            setTimeout(initSelect2Selects, 100);
        };
        document.head.appendChild(script);
    }

function initSelect2Selects() {
    console.log('Инициализируем Select2');
    
    try {
        // Обычные селекты с поиском
        const singleSelects = ['#object_type', '#service_type', '#work_type', '#work_details'];
        
        singleSelects.forEach(selector => {
            const $select = jQuery(selector);
            if ($select.length && !$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    placeholder: 'Начните вводить для поиска...',
                    allowClear: true,
                    width: '100%',
                    language: 'ru',
                    minimumInputLength: 0,
                    escapeMarkup: function(markup) {
                        return markup;
                    }
                });
            }
        });
        
        // Множественные селекты
        const multiSelects = ['#pollution_degree', '#parameters'];
        
        multiSelects.forEach(selector => {
            const $select = jQuery(selector);
            if ($select.length && !$select.hasClass('select2-hidden-accessible')) {
                $select.select2({
                    placeholder: 'Выберите несколько вариантов...',
                    allowClear: true,
                    width: '100%',
                    multiple: true,
                    language: 'ru',
                    minimumInputLength: 0,
                    closeOnSelect: false
                });
                
                // Обработчики для множественных селектов
                $select.on('select2:select.calculator select2:unselect.calculator', function() {
                    if (selector === '#pollution_degree') {
                        calculatePollutionSum();
                    } else if (selector === '#parameters') {
                        calculateParametersSum();
                    }
                });
            }
        });
        
        addSelect2Styles();
        
        // Настраиваем обработчики событий
        setTimeout(() => {
            setupObjectTypeHandler();
            setupServiceTypeHandler();
            setupWorkTypeHandler();
            setupWorkDetailsHandler();
        }, 100);
        
        console.log('Select2 успешно инициализирован');
        
    } catch (error) {
        console.error('Ошибка инициализации Select2:', error);
    }
}

    function addSelect2Styles() {
        const style = document.createElement('style');
        style.textContent = `
        /* Стили для Select2 в Битрикс */
        .select2-container {
            z-index: 9999 !important;
        }
        
        .select2-container--default .select2-selection--single {
            height: 40px !important;
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 36px !important;
            padding-left: 8px !important;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            min-height: 40px !important;
        }
        
        .select2-dropdown {
            border: 1px solid #ddd !important;
            border-radius: 4px !important;
            z-index: 99999 !important;
        }
        
        .select2-search--inline .select2-search__field {
            height: 30px !important;
            margin-top: 0 !important;
        }
        
        /* Для disabled селектов */
        .select2-container--default.select2-container--disabled .select2-selection--single,
        .select2-container--default.select2-container--disabled .select2-selection--multiple {
            background-color: #f5f5f5 !important;
            cursor: not-allowed !important;
        }
    `;
        document.head.appendChild(style);
    }

    // Функция для обновления Select2 при изменении опций
    function updateSelect2Options(selectId, newOptions) {
        const $select = jQuery('#' + selectId);
        if ($select.length && $select.hasClass('select2-hidden-accessible')) {
            // Сохраняем текущее значение
            const currentValue = $select.val();

            // Очищаем селект
            $select.empty();

            // Добавляем новые опции
            newOptions.forEach(option => {
                $select.append(new Option(option.text, option.value));
            });

            // Обновляем Select2
            $select.trigger('change.select2');

            // Восстанавливаем значение если возможно
            if (currentValue && $select.find(`option[value="${currentValue}"]`).length) {
                $select.val(currentValue).trigger('change.select2');
            }
        }
    }

    // Запуск
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initCalculator();
            loadSelect2();
            setTimeout(() => {
                addRefreshButtons();
            }, 500);
        });
    } else {
        setTimeout(function() {
            initCalculator();
            loadSelect2();
            setTimeout(() => {
                addRefreshButtons();
            }, 500);
        }, 100);
    }
    const refreshStyles = document.createElement('style');
    refreshStyles.textContent = `
    .refresh-calculator-btn, .refresh-products-btn {
        transition: all 0.2s ease;
    }
    .refresh-calculator-btn:hover, .refresh-products-btn:hover {
        transform: scale(1.05);
    }
`;
    document.head.appendChild(refreshStyles);
</script>