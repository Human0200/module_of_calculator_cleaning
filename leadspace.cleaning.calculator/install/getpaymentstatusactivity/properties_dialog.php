<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="2">
            <b><?= GetMessage("TBANK_PAYMENT_ACTIVITY_TITLE") ?></b>
            <hr>
        </td>
    </tr>
    
    <!-- Информация о получателе -->
    <tr>
        <td colspan="2">
            <h3><?= GetMessage("TBANK_PAYMENT_RECIPIENT_INFO") ?></h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_RECIPIENT_NAME") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'recipient_name', $arCurrentValues['recipient_name'], ['size' => '50']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_RECIPIENT_INN") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'recipient_inn', $arCurrentValues['recipient_inn'], ['size' => '20', 'maxlength' => '12']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_RECIPIENT_ACCOUNT") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'recipient_account', $arCurrentValues['recipient_account'], ['size' => '30', 'maxlength' => '20']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_RECIPIENT_BANK_BIK") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'recipient_bank_bik', $arCurrentValues['recipient_bank_bik'], ['size' => '15', 'maxlength' => '9']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Корр. счет банка получателя</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'recipient_corr_account_number', $arCurrentValues['recipient_corr_account_number'] ?? '', ['size' => '30', 'maxlength' => '20']) ?>
        </td>
    </tr>
    
    <!-- Информация о платеже -->
    <tr>
        <td colspan="2">
            <h3><?= GetMessage("TBANK_PAYMENT_INFO") ?></h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_AMOUNT") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("double", 'amount', $arCurrentValues['amount'], ['size' => '20']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_PURPOSE") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("text", 'payment_purpose', $arCurrentValues['payment_purpose'], ['rows' => '3', 'cols' => '50']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_PAYER_ACCOUNT") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'payer_account', $arCurrentValues['payer_account'], ['size' => '30', 'maxlength' => '20']) ?>
        </td>
    </tr>

    <!-- Дополнительные поля для физ. лиц -->
    <tr>
        <td colspan="2">
            <h3>Дополнительные поля для физ. лиц</h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Код вида выплаты</b>:
        </td>
        <td width="60%">
            <select name="revenue_type_code">
                <option value="">Не указан</option>
                <option value="1" <?= ($arCurrentValues['revenue_type_code'] === '1') ? 'selected' : '' ?>>1 - Заработная плата</option>
                <option value="2" <?= ($arCurrentValues['revenue_type_code'] === '2') ? 'selected' : '' ?>>2 - Прочие доходы</option>
                <option value="3" <?= ($arCurrentValues['revenue_type_code'] === '3') ? 'selected' : '' ?>>3 - Дивиденды</option>
                <option value="4" <?= ($arCurrentValues['revenue_type_code'] === '4') ? 'selected' : '' ?>>4 - Оплата за гражданско-правовой договор</option>
                <option value="5" <?= ($arCurrentValues['revenue_type_code'] === '5') ? 'selected' : '' ?>>5 - Авторские вознаграждения</option>
            </select>
            <br><small>Обязательное поле для платежей в пользу физ. лиц</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Удержанная сумма</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("double", 'collection_amount_number', $arCurrentValues['collection_amount_number'] ?? '', ['size' => '20']) ?>
            <br><small>Удержанная сумма из заработной платы и иных доходов работника в рублях</small>
        </td>
    </tr>
    
    <!-- Налоговые поля -->
    <tr>
        <td colspan="2">
            <h3>Налоговые поля (обязательные для API)</h3>
            <div style="background-color: #fffbe5; padding: 10px; border-left: 4px solid #ffa500; margin: 10px 0;">
                <strong>Внимание:</strong> Для небюджетных платежей все поля должны содержать значение "0"
            </div>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Статус составителя (поле 101)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tax_payer_status', $arCurrentValues['tax_payer_status'] ?? '0', ['size' => '10']) ?>
            <br><small>Для небюджетных платежей указывайте "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>КБК (поле 104)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'kbk', $arCurrentValues['kbk'] ?? '0', ['size' => '25']) ?>
            <br><small>Код бюджетной классификации. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>ОКТМО</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'oktmo', $arCurrentValues['oktmo'] ?? '0', ['size' => '15']) ?>
            <br><small>Код ОКТМО. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Основание платежа (поле 106)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tax_evidence', $arCurrentValues['tax_evidence'] ?? '0', ['size' => '10']) ?>
            <br><small>Основание налогового платежа. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Налоговый период (поле 107)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tax_period', $arCurrentValues['tax_period'] ?? '0', ['size' => '15']) ?>
            <br><small>Формат: ДД.ММ.ГГГГ. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Номер документа (поле 108)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tax_doc_number', $arCurrentValues['tax_doc_number'] ?? '0', ['size' => '20']) ?>
            <br><small>Номер налогового документа. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b>Дата документа (поле 109)</b>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tax_doc_date', $arCurrentValues['tax_doc_date'] ?? '0', ['size' => '15']) ?>
            <br><small>Дата налогового документа. Формат: ДД.ММ.ГГГГ. Для небюджетных платежей - "0"</small>
        </td>
    </tr>
    
    <!-- Настройки API -->
    <tr>
        <td colspan="2">
            <h3><?= GetMessage("TBANK_PAYMENT_API_SETTINGS") ?></h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_TOKEN") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tbank_token', $arCurrentValues['tbank_token'], ['size' => '50']) ?>
            <br><small><?= GetMessage("TBANK_PAYMENT_TOKEN_HINT") ?></small>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_PAYMENT_SANDBOX_MODE") ?></b>:
        </td>
        <td width="60%">
            <select name="is_sandbox">
                <option value="Y" <?= ($arCurrentValues['is_sandbox'] === 'Y' || empty($arCurrentValues['is_sandbox'])) ? 'selected' : '' ?>>
                    <?= GetMessage("TBANK_PAYMENT_SANDBOX_YES") ?>
                </option>
                <option value="N" <?= ($arCurrentValues['is_sandbox'] === 'N') ? 'selected' : '' ?>>
                    <?= GetMessage("TBANK_PAYMENT_SANDBOX_NO") ?>
                </option>
            </select>
            <br><small><?= GetMessage("TBANK_PAYMENT_SANDBOX_HINT") ?></small>
        </td>
    </tr>
    
    <!-- Дополнительная информация -->
    <tr>
        <td colspan="2">
            <hr>
            <div style="background-color: #f0f8ff; padding: 10px; border-left: 4px solid #1e90ff; margin: 10px 0;">
                <h4><?= GetMessage("TBANK_PAYMENT_HELP_TITLE") ?></h4>
                <ul>
                    <li><?= GetMessage("TBANK_PAYMENT_HELP_1") ?></li>
                    <li><?= GetMessage("TBANK_PAYMENT_HELP_2") ?></li>
                    <li><?= GetMessage("TBANK_PAYMENT_HELP_3") ?></li>
                    <li><?= GetMessage("TBANK_PAYMENT_HELP_4") ?></li>
                    <li><strong>Новое:</strong> Все налоговые поля обязательны для API T-Bank</li>
                    <li><strong>Для небюджетных платежей:</strong> Все налоговые поля должны содержать значение "0"</li>
                    <li><strong>Для физ. лиц:</strong> Рекомендуется указать код вида выплаты</li>
                </ul>
            </div>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">
            <div style="background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 10px 0;">
                <h4>Важная информация о налоговых полях:</h4>
                <ul>
                    <li><strong>Поле 101 (Статус составителя):</strong> Для обычных переводов = "0"</li>
                    <li><strong>Поле 104 (КБК):</strong> Для небюджетных платежей = "0"</li>
                    <li><strong>ОКТМО:</strong> Для небюджетных платежей = "0"</li>
                    <li><strong>Поля 106-109:</strong> Для небюджетных платежей = "0"</li>
                    <li><strong>Если это налоговый платеж:</strong> Заполните соответствующие поля согласно требованиям</li>
                </ul>
            </div>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">
            <small style="color: red;"><?= GetMessage("TBANK_PAYMENT_REQUIRED_FIELDS") ?></small>
        </td>
    </tr>
</table>

<style>
    h3 {
        color: #2c5aa0;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 5px;
        margin-bottom: 15px;
        margin-top: 20px;
    }
    
    small {
        color: #666;
        font-style: italic;
    }
    
    span[style*="color: red"] {
        font-weight: bold;
    }
    
    .help-section {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
    }
    
    /* Стили для различных типов уведомлений */
    div[style*="background-color: #fffbe5"] {
        border-radius: 5px;
    }
    
    div[style*="background-color: #f0f8ff"] {
        border-radius: 5px;
    }
    
    div[style*="background-color: #fff3cd"] {
        border-radius: 5px;
    }
</style>