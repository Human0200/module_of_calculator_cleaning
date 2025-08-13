console.log('JavaScript загружен!');

    // Простая инициализация без сложной логики
    function initCalculator() {
        console.log('Инициализация калькулятора');

        const objectTypeSelect = document.getElementById('object_type');
        const serviceTypeSelect = document.getElementById('service_type');
        const workTypeSelect = document.getElementById('work_type');
        const workDetailsSelect = document.getElementById('work_details');
        const workVolumeInput = document.getElementById('work_volume');
        const workUnitSelect = document.getElementById('work_unit');

        if (!objectTypeSelect || !serviceTypeSelect || !workTypeSelect || !workDetailsSelect) {
            console.error('Селекты не найдены!');
            return;
        }

        console.log('Все селекты найдены, добавляем обработчики');

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

            if (this.value) {
                serviceTypeSelect.innerHTML = '<option value="">Загрузка услуг...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_services&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для услуг:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log('AJAX ответ услуги:', xhr.status, xhr.responseText);

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

            if (this.value) {
                workTypeSelect.innerHTML = '<option value="">Загрузка видов работ...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_types&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для видов работ:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log('AJAX ответ виды работ:', xhr.status, xhr.responseText);

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

            if (this.value) {
                workDetailsSelect.innerHTML = '<option value="">Загрузка товаров...</option>';

                const xhr = new XMLHttpRequest();
                const url = `/local/ajax/calculator_ajax_handler.php?action=get_work_details&parent_id=${this.value}&sessid=<?= bitrix_sessid() ?>`;

                console.log('AJAX URL для товаров:', url);

                xhr.open('GET', url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        console.log('AJAX ответ товары:', xhr.status, xhr.responseText);

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
                // Загружаем свойства товара и автоматически проставляем степени загрязнения и параметры
                loadProductPropertiesAndSetSelects(productId);
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
                    object_type: objectTypeSelect.value,
                    service_type: serviceTypeSelect.value,
                    work_type: workTypeSelect.value,
                    work_details: workDetailsSelect.value,
                    pollution_degree: pollutionDegreeValues, // Массив значений
                    parameters: parametersValues, // Массив значений
                    work_volume: workVolumeInput.value,
                    work_unit: workUnitSelect.value,
                    special_notes: document.getElementById('special_notes').value
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
                
                // AJAX запрос
                BX.ajax({
                    method: 'POST',
                    url: '/local/ajax/calculator_ajax_handler.php',
                    data: {
                        action: 'add_product_to_deal',
                        deal_id: formData.deal_id,
                        product_id: formData.work_details, // ID выбранного товара
                        quantity: formData.work_volume,
                        unit: formData.work_unit,
                        price: 0, // Будет получена из товара
                        object_type: formData.object_type,
                        service_type: formData.service_type,
                        work_type: formData.work_type,
                        pollution_degree: JSON.stringify(formData.pollution_degree), // Передаем как JSON строку
                        parameters: JSON.stringify(formData.parameters), // Передаем как JSON строку
                        special_notes: formData.special_notes,
                        sessid: '<?= bitrix_sessid() ?>'
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
                                
                                // Очищаем форму
                                clearCalculatorForm();
                                
                                // Обновляем таблицу товаров
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                                
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

        // Тест
        console.log('Опции в селекте:');
        for (let i = 0; i < objectTypeSelect.options.length; i++) {
            console.log(`${i}: ${objectTypeSelect.options[i].value} - ${objectTypeSelect.options[i].text}`);
        }
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
                                console.log('Автоматически проставлены степени загрязнения:', pollutionProperties);
                            }
                            
                            // Проставляем параметры
                            if (parameterProperties.length > 0) {
                                setParametersValues(parameterProperties);
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
        
        // Сначала сбрасываем все выбранные опции
        Array.from(pollutionSelect.options).forEach(option => {
            option.selected = false;
        });
        
        // Проставляем нужные опции
        Array.from(pollutionSelect.options).forEach(option => {
            if (values.includes(option.value)) {
                option.selected = true;
                console.log('Опция выбрана:', option.value, option.text);
            }
        });
        
        // Визуально обновляем селект (может помочь с отображением)
        pollutionSelect.dispatchEvent(new Event('change'));
    }

    function setParametersValues(values) {
        console.log('Устанавливаем параметры:', values);
        
        const parametersSelect = document.getElementById('parameters');
        if (!parametersSelect) {
            console.error('Селект параметров не найден!');
            return;
        }
        
        // Сначала сбрасываем все выбранные опции
        Array.from(parametersSelect.options).forEach(option => {
            option.selected = false;
        });
        
        // Проставляем нужные опции
        Array.from(parametersSelect.options).forEach(option => {
            if (values.includes(option.value)) {
                option.selected = true;
                console.log('Параметр выбран:', option.value, option.text);
            }
        });
        
        // Визуально обновляем селект
        parametersSelect.dispatchEvent(new Event('change'));
    }

    function resetPollutionDegree() {
        console.log('Сбрасываем степени загрязнения');
        
        const pollutionSelect = document.getElementById('pollution_degree');
        if (pollutionSelect) {
            Array.from(pollutionSelect.options).forEach(option => {
                option.selected = false;
            });
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