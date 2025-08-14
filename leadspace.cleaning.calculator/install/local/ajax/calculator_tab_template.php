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
                                            <small style="color: #999;">Удерживайте Ctrl (Cmd на Mac) для множественного выбора</small>
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
                                                style="width: 120px;">
                                                <option value="">Выберите единицу</option>
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
                                            <small style="color: #999;">Удерживайте Ctrl (Cmd на Mac) для множественного выбора</small>
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
    <div class="crm-entity-section">
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
    console.log('JavaScript загружен!');

    // Глобальные переменные для хранения всех опций
    let allPollutionOptions = [];
    let allParametersOptions = [];


    // Простая инициализация без сложной логики
    function initCalculator() {
        console.log('Инициализация калькулятора');

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

        console.log('Все селекты найдены, добавляем обработчики');

        // Добавляем обработчики для селектов с множественным выбором
        addMultiSelectHandlers();

        // Обработчик для типа объекта
        objectTypeSelect.addEventListener('change', function() {
            console.log('CHANGE EVENT - Тип объекта! Выбрано:', this.value);

            // Сбрасываем зависимые селекты
            serviceTypeSelect.innerHTML = '<option value="">Сначала выберите тип объекта</option>';
            serviceTypeSelect.disabled = true;
            workTypeSelect.innerHTML = '<option value="">Сначала выберите вид услуги</option>';
            workTypeSelect.disabled = true;
            workDetailsSelect.innerHTML = '<option value="">Сначала выберите вид работ</option>';
            workDetailsSelect.disabled = true;

            // Сбрасываем степени загрязнения и параметры
            resetPollutionDegree();
            resetParameters();
            disablePollutionAndParameters();

            if (this.value) {
                serviceTypeSelect.innerHTML = '<option value="">Загрузка услуг...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_services&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для услуг:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        //console.log('AJAX ответ услуги:', xhr.status, xhr.responseText);

                        if (xhr.status === 200) {
                            try {
                                const data = JSON.parse(xhr.responseText);
                                console.log('Получены услуги:', data);

                                if (data && data.length > 0) {
                                    let html = '<option value="">Выберите вид услуги</option>';
                                    data.forEach(item => {
                                        html += `<option value="${item.ID}">${item.NAME}</option>`;
                                    });
                                    serviceTypeSelect.innerHTML = html;
                                    serviceTypeSelect.disabled = false;
                                    console.log('Селект услуг заполнен!');
                                } else {
                                    serviceTypeSelect.innerHTML = '<option value="">Услуги не найдены</option>';
                                }
                            } catch (e) {
                                console.error('Ошибка JSON услуги:', e);
                            }
                        }
                    }
                };
                xhr.send();
            }
        });

        // Обработчик для вида услуги
        serviceTypeSelect.addEventListener('change', function() {
            console.log('CHANGE EVENT - Вид услуги! Выбрано:', this.value);

            // Сбрасываем зависимые селекты
            workTypeSelect.innerHTML = '<option value="">Сначала выберите вид услуги</option>';
            workTypeSelect.disabled = true;
            workDetailsSelect.innerHTML = '<option value="">Сначала выберите вид работ</option>';
            workDetailsSelect.disabled = true;

            // Сбрасываем степени загрязнения и параметры
            resetPollutionDegree();
            resetParameters();
            disablePollutionAndParameters();

            if (this.value) {
                workTypeSelect.innerHTML = '<option value="">Загрузка видов работ...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_types&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для видов работ:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        //console.log('AJAX ответ виды работ:', xhr.status, xhr.responseText);

                        if (xhr.status === 200) {
                            try {
                                const data = JSON.parse(xhr.responseText);
                                console.log('Получены виды работ:', data);

                                if (data && data.length > 0) {
                                    let html = '<option value="">Выберите вид работ</option>';
                                    data.forEach(item => {
                                        html += `<option value="${item.ID}">${item.NAME}</option>`;
                                    });
                                    workTypeSelect.innerHTML = html;
                                    workTypeSelect.disabled = false;
                                    console.log('Селект видов работ заполнен!');
                                } else {
                                    workTypeSelect.innerHTML = '<option value="">Виды работ не найдены</option>';
                                }
                            } catch (e) {
                                console.error('Ошибка JSON виды работ:', e);
                            }
                        }
                    }
                };
                xhr.send();
            }
        });

        // Обработчик для вида работ
        workTypeSelect.addEventListener('change', function() {
            console.log('CHANGE EVENT - Вид работ! Выбрано:', this.value);

            // Сбрасываем зависимый селект
            workDetailsSelect.innerHTML = '<option value="">Сначала выберите вид работ</option>';
            workDetailsSelect.disabled = true;

            // Сбрасываем степени загрязнения и параметры
            resetPollutionDegree();
            resetParameters();
            disablePollutionAndParameters();

            if (this.value) {
                workDetailsSelect.innerHTML = '<option value="">Загрузка товаров...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_details&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для товаров:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        //console.log('AJAX ответ товары:', xhr.status, xhr.responseText);

                        if (xhr.status === 200) {
                            try {
                                const data = JSON.parse(xhr.responseText);
                                console.log('Получены товары:', data);

                                if (data && data.length > 0) {
                                    let html = '<option value="">Выберите услугу/товар</option>';
                                    data.forEach(item => {
                                        html += `<option value="${item.ID}">${item.NAME}</option>`;
                                    });
                                    workDetailsSelect.innerHTML = html;
                                    workDetailsSelect.disabled = false;
                                    console.log('Селект товаров заполнен!');
                                } else {
                                    workDetailsSelect.innerHTML = '<option value="">Товары не найдены</option>';
                                }
                            } catch (e) {
                                console.error('Ошибка JSON товары:', e);
                            }
                        }
                    }
                };
                xhr.send();
            }
        });

        // НОВЫЙ обработчик для детализации работ
        workDetailsSelect.addEventListener('change', function() {
            console.log('CHANGE EVENT - Детализация работ! Выбрано:', this.value);

            const productId = this.value;

            // Сбрасываем степени загрязнения и параметры
            resetPollutionDegree();
            resetParameters();

            if (productId) {
                // Включаем селекты
                enablePollutionAndParameters();

                // Загружаем свойства товара и автоматически проставляем степени загрязнения и параметры
                loadProductPropertiesAndSetSelects(productId);
            } else {
                // Отключаем селекты если товар не выбран
                disablePollutionAndParameters();
            }
        });

        // Обработчик кнопки расчета
        const calculateBtn = document.getElementById('calculate_btn');
        if (calculateBtn) {
            calculateBtn.addEventListener('click', function() {
                console.log('Кнопка расчета нажата');

                if (!window.CURRENT_DEAL_ID || window.CURRENT_DEAL_ID <= 0) {
                    alert('Не удалось определить ID сделки');
                    return;
                }

                const dealid = window.CURRENT_DEAL_ID;
                console.log('ID текущей сделки:', dealid);

                // Получаем выбранные степени загрязнения и параметры
                const pollutionDegreeValues = getPollutionDegreeValues();
                const parametersValues = getParametersValues();
                console.log('Выбранные степени загрязнения:', pollutionDegreeValues);
                console.log('Выбранные параметры:', parametersValues);

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

                console.log('Данные формы:', formData);

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

                console.log('Отправляем данные на расчет:', formData);
                const coefficient = parseFloat(document.getElementById('coefficient').innerText) + parseFloat(document.getElementById('coefficient_parameters').innerText) || 1.0;
                console.log('Коэффициент:', coefficient);
                volume_price = parseFloat(document.getElementById('additional_cost').innerText);
                volume = document.getElementById('work_volume').value;
                work_unit = document.getElementById('work_unit').value;
                console.log('Объем работ:', volume, 'Единица измерения:', work_unit, 'Цена за единицу:', volume_price);
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
                        console.log('Результат расчета:', result);

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
                            console.log('Ответ сервера:', result);
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

    function calculateWorkVolumeSum() {
        const workVolume = document.getElementById('work_volume').value;
        const workUnit = document.getElementById('work_unit').value;
        const workDetails = document.getElementById('work_details').value;

        if (!workVolume || workVolume <= 0) {
            console.log('Объем работ не указан');
            document.getElementById('additional_cost').innerText = '0.00';
            return;
        }

        console.log('Ищем цену для объема работ:', workVolume);

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
                console.log('AJAX ответ поиска цены:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            console.log('=== НАЙДЕННАЯ ЦЕНА ===');
                            console.log('Цена за единицу:', data.unit_price);
                            console.log('Найденный диапазон:', data.found_item);

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
        console.log('Обновляем раздел товаров по сделке');

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

                            console.log('Раздел товаров успешно обновлен');

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

    function addRefreshButtonToProductsSection() {
        const productsSections = document.querySelectorAll('.crm-entity-section');

        productsSections.forEach(section => {
            const title = section.querySelector('.crm-entity-section-title-text');
            if (title && title.textContent.includes('Товары по сделке')) {
                const titleContainer = section.querySelector('.crm-entity-section-title');

                // Проверяем, не добавлена ли уже кнопка
                if (titleContainer.querySelector('.refresh-products-btn')) {
                    return;
                }

                const refreshBtn = document.createElement('button');
                refreshBtn.className = 'ui-btn ui-btn-xs ui-btn-light-border refresh-products-btn';
                refreshBtn.innerHTML = '↻ Обновить';
                refreshBtn.style.marginLeft = '10px';
                refreshBtn.title = 'Обновить список товаров';

                refreshBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    reloadDealProductsSection();
                });

                titleContainer.appendChild(refreshBtn);
            }
        });
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

        const pollutionSelect = document.getElementById('pollution_degree');
        const parametersSelect = document.getElementById('parameters');

        if (pollutionSelect) {
            pollutionSelect.disabled = false;
            console.log('Селект pollution_degree включен');
        }

        if (parametersSelect) {
            parametersSelect.disabled = false;
            console.log('Селект parameters включен');
        }
    }

    // Функция отключения селектов Чек-бокс и Параметры
    function disablePollutionAndParameters() {
        console.log('Отключаем селекты Чек-бокс и Параметры');

        const pollutionSelect = document.getElementById('pollution_degree');
        const parametersSelect = document.getElementById('parameters');

        if (pollutionSelect) {
            pollutionSelect.disabled = true;
            console.log('Селект pollution_degree отключен');
        }

        if (parametersSelect) {
            parametersSelect.disabled = true;
            console.log('Селект parameters отключен');
        }
    }

    // Функция добавления обработчиков для множественного выбора
    function addMultiSelectHandlers() {
        console.log('Добавляем обработчики для множественного выбора');

        const pollutionSelect = document.getElementById('pollution_degree');
        const parametersSelect = document.getElementById('parameters');

        if (pollutionSelect) {
            pollutionSelect.addEventListener('change', function() {
                console.log('Изменились степени загрязнения');
                //hideUnselectedOptions('pollution_degree');

                // НОВАЯ ФУНКЦИЯ: расчет суммы при изменении чек-боксов
                calculatePollutionSum();
            });
        }

        if (parametersSelect) {
            parametersSelect.addEventListener('change', function() {
                console.log('Изменились параметры');
                calculateParametersSum();
                //hideUnselectedOptions('parameters');

                // НОВАЯ ФУНКЦИЯ: расчет суммы параметров при изменении
                // calculateParametersSum();
            });
        }
    }

    // НОВАЯ ФУНКЦИЯ: Расчет суммы из селекта "Чек-бокс"
    function calculatePollutionSum() {
        const pollutionValues = getPollutionDegreeValues();

        if (pollutionValues.length === 0) {
            console.log('Степени загрязнения не выбраны');
            document.getElementById('coefficient').innerText = '0.0';
            return;
        }

        console.log('Рассчитываем сумму для степеней загрязнения:', pollutionValues);

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
                console.log('AJAX ответ расчет суммы загрязнения:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            console.log('=== РЕЗУЛЬТАТ РАСЧЕТА СТЕПЕНЕЙ ЗАГРЯЗНЕНИЯ ===');
                            console.log('Общая сумма:', data.total_sum);
                            console.log('Обработанные элементы:', data.processed_items);
                            console.log('Поле:', data.field_name);

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
            console.log('Параметры не выбраны');
            document.getElementById('coefficient_parameters').innerText = '0.0';
            return;
        }

        console.log('Рассчитываем сумму для параметров:', parametersValues);

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
                console.log('AJAX ответ расчет суммы параметров:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            console.log('=== РЕЗУЛЬТАТ РАСЧЕТА ПАРАМЕТРОВ ===');
                            console.log('Общая сумма параметров:', data.total_sum);
                            console.log('Обработанные элементы:', data.processed_items);

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
        console.log('Скрываем невыбранные опции для:', selectId);

        const select = document.getElementById(selectId);
        if (!select) return;

        const selectedValues = Array.from(select.selectedOptions).map(option => option.value);
        console.log('Выбранные значения:', selectedValues);

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

        const select = document.getElementById(selectId);
        if (!select) return;

        Array.from(select.options).forEach(option => {
            option.style.display = '';
        });
    }

    // Функция загрузки свойств товара и установки степеней загрязнения и параметров
    function loadProductPropertiesAndSetSelects(productId) {
        console.log('Загружаем свойства товара:', productId);

        const xhr = new XMLHttpRequest();
        const url = `/local/ajax/calculator_ajax_handler.php?action=get_product_properties&product_id=${productId}&sessid=<?= bitrix_sessid() ?>`;

        console.log('AJAX URL для свойств товара:', url);

        xhr.open('GET', url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                console.log('AJAX ответ свойства товара:', xhr.status, xhr.responseText);

                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        console.log('Получены свойства товара:', data);

                        if (data.success) {
                            // Получаем разные свойства
                            const pollutionProperties = data.pollution_properties || [];
                            const parameterProperties = data.parameter_properties || [];

                            console.log('Степени загрязнения:', pollutionProperties);
                            console.log('Параметры:', parameterProperties);

                            // Проставляем степени загрязнения
                            if (pollutionProperties.length > 0) {
                                setPollutionDegreeValues(pollutionProperties);
                                // Скрываем невыбранные опции
                                hideUnselectedOptions('pollution_degree');
                                console.log('Автоматически проставлены степени загрязнения:', pollutionProperties);
                            }

                            // Проставляем параметры
                            if (parameterProperties.length > 0) {
                                setParametersValues(parameterProperties);
                                // Скрываем невыбранные опции
                                hideUnselectedOptions('parameters');
                                console.log('Автоматически проставлены параметры:', parameterProperties);
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
                                console.log('Для данного товара нет автоматических настроек');
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
        console.log('Устанавливаем степени загрязнения:', values);

        const pollutionSelect = document.getElementById('pollution_degree');
        if (!pollutionSelect) {
            console.error('Селект степени загрязнения не найден!');
            return;
        }

        return new Promise((resolve) => {
            // ЭТАП 1: Выбираем опции
            Array.from(pollutionSelect.options).forEach(option => {
                if (values.includes(option.value)) {
                    option.selected = true;
                    console.log('Опция выбрана:', option.value, option.text);
                }
            });

            // Даем время браузеру обработать изменения
            requestAnimationFrame(() => {
                // ЭТАП 2: Снимаем выделение
                Array.from(pollutionSelect.options).forEach(option => {
                    option.selected = false;
                });

                console.log('Все опции сняты с выделения');
                pollutionSelect.dispatchEvent(new Event('change'));
                resolve();
            });
        });
    }

    function setParametersValues(values) {
        console.log('Устанавливаем параметры:', values);

        const parametersSelect = document.getElementById('parameters');
        if (!parametersSelect) {
            console.error('Селект параметров не найден!');
            return;
        }

        return new Promise((resolve) => {
            // ЭТАП 1: Выбираем опции
            Array.from(parametersSelect.options).forEach(option => {
                if (values.includes(option.value)) {
                    option.selected = true;
                    console.log('Параметр выбран:', option.value, option.text);
                }
            });

            // Даем время браузеру обработать изменения
            requestAnimationFrame(() => {
                // ЭТАП 2: Снимаем выделение
                Array.from(parametersSelect.options).forEach(option => {
                    option.selected = false;
                });

                console.log('Все параметры сняты с выделения');
                parametersSelect.dispatchEvent(new Event('change'));
                resolve();
            });
        });
    }

    function resetPollutionDegree() {
        console.log('Сбрасываем степени загрязнения');

        const pollutionSelect = document.getElementById('pollution_degree');
        if (pollutionSelect) {
            Array.from(pollutionSelect.options).forEach(option => {
                option.selected = false;
            });
            // Восстанавливаем все опции
            restoreAllOptions('pollution_degree');
            pollutionSelect.dispatchEvent(new Event('change'));
        }
    }

    function resetParameters() {
        console.log('Сбрасываем параметры');

        const parametersSelect = document.getElementById('parameters');
        if (parametersSelect) {
            Array.from(parametersSelect.options).forEach(option => {
                option.selected = false;
            });
            // Восстанавливаем все опции
            restoreAllOptions('parameters');
            parametersSelect.dispatchEvent(new Event('change'));
        }
    }

    function getPollutionDegreeValues() {
        const pollutionSelect = document.getElementById('pollution_degree');
        if (pollutionSelect && pollutionSelect.multiple) {
            return Array.from(pollutionSelect.selectedOptions).map(option => option.value);
        }
        return [];
    }

    function getParametersValues() {
        const parametersSelect = document.getElementById('parameters');
        if (parametersSelect && parametersSelect.multiple) {
            return Array.from(parametersSelect.selectedOptions).map(option => option.value);
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

    function initCopyProductButtons() {
        console.log('Инициализируем кнопки копирования товаров');

        const copyButtons = document.querySelectorAll('.copy-product-btn');

        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                console.log('Кнопка копирования нажата');

                // Получаем данные из data-атрибутов
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = this.getAttribute('data-product-price');
                const productQuantity = this.getAttribute('data-product-quantity');
                const productUnit = this.getAttribute('data-product-unit');

                console.log('Копируем товар:', {
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                    unit: productUnit
                });

                // Заполняем форму калькулятора данными товара
                copyProductToCalculator({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: productQuantity,
                    unit: productUnit
                });

                // Показываем уведомление
                if (typeof BX !== 'undefined' && BX.UI && BX.UI.Notification) {
                    BX.UI.Notification.Center.notify({
                        content: `Товар "${productName}" скопирован в калькулятор`,
                        position: "top-right",
                        autoHideDelay: 3000
                    });
                } else {
                    alert(`Товар "${productName}" скопирован в калькулятор`);
                }
            });
        });
    }

    function copyProductToCalculator(product) {
        console.log('Заполняем калькулятор данными товара:', product);

        // Заполняем объем работ
        const workVolumeInput = document.getElementById('work_volume');
        if (workVolumeInput) {
            workVolumeInput.value = product.quantity;
        }

        // Устанавливаем единицу измерения
        const workUnitSelect = document.getElementById('work_unit');
        if (workUnitSelect) {
            // Пытаемся найти соответствующую единицу измерения
            const unitMapping = {
                'шт': 'pcs',
                'штук': 'pcs',
                'м²': 'm2',
                'кв.м': 'm2',
                'м': 'm',
                'кг': 'kg',
                'л': 'l',
                'час': 'hour',
                'часов': 'hour',
                'день': 'day',
                'дней': 'day',
                'м³': 'm3',
                'упак': 'pack',
                'услуга': 'service'
            };

            const mappedUnit = unitMapping[product.unit.toLowerCase()] || 'pcs';
            workUnitSelect.value = mappedUnit;
        }

        // Добавляем информацию в особые пометки
        const specialNotesTextarea = document.getElementById('special_notes');
        if (specialNotesTextarea) {
            const copyNote = `\nСкопировано из товаров сделки: ${product.name} (Цена: ${product.price} ₽)`;

            if (specialNotesTextarea.value.trim()) {
                specialNotesTextarea.value += copyNote;
            } else {
                specialNotesTextarea.value = `Скопировано из товаров сделки: ${product.name} (Цена: ${product.price} ₽)`;
            }
        }

        // Прокручиваем к калькулятору
        const calculatorSection = document.querySelector('.calculator-tab-wrapper');
        if (calculatorSection) {
            calculatorSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // Запуск
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCalculator);
    } else {
        setTimeout(initCalculator, 100);
    }
</script>