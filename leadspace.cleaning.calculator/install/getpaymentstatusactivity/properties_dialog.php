<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tr>
        <td colspan="2">
            <b><?= GetMessage("TBANK_STATUS_ACTIVITY_TITLE") ?></b>
            <hr>
        </td>
    </tr>
    
    <!-- Основные параметры -->
    <tr>
        <td colspan="2">
            <h3><?= GetMessage("TBANK_STATUS_MAIN_INFO") ?></h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_STATUS_DOCUMENT_IDS") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("text", 'document_ids', $arCurrentValues['document_ids'], ['rows' => '4', 'cols' => '70']) ?>
            <br><small><?= GetMessage("TBANK_STATUS_DOCUMENT_IDS_HINT") ?></small>
        </td>
    </tr>
    
    <!-- Настройки API -->
    <tr>
        <td colspan="2">
            <h3><?= GetMessage("TBANK_STATUS_API_SETTINGS") ?></h3>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_STATUS_TOKEN") ?></b><span style="color: red;">*</span>:
        </td>
        <td width="60%">
            <?= CBPDocument::ShowParameterField("string", 'tbank_token', $arCurrentValues['tbank_token'], ['size' => '50']) ?>
        </td>
    </tr>
    
    <tr>
        <td align="right" width="40%">
            <b><?= GetMessage("TBANK_STATUS_SANDBOX_MODE") ?></b>:
        </td>
        <td width="60%">
            <select name="is_sandbox">
                <option value="Y" <?= ($arCurrentValues['is_sandbox'] === 'Y' || empty($arCurrentValues['is_sandbox'])) ? 'selected' : '' ?>>
                    <?= GetMessage("TBANK_STATUS_SANDBOX_YES") ?>
                </option>
                <option value="N" <?= ($arCurrentValues['is_sandbox'] === 'N') ? 'selected' : '' ?>>
                    <?= GetMessage("TBANK_STATUS_SANDBOX_NO") ?>
                </option>
            </select>
            <br><small><?= GetMessage("TBANK_STATUS_SANDBOX_HINT") ?></small>
        </td>
    </tr>
    
    <!-- Примеры использования -->
    <tr>
        <td colspan="2">
            <hr>
            <div style="background-color: #f0f8ff; padding: 10px; border-left: 4px solid #1e90ff; margin: 10px 0;">
                <h4>Примеры форматов ID документов:</h4>
                <ul>
                    <li><strong>Один документ:</strong><br>3fa85f64-5717-4562-b3fc-2c963f66afa6</li>
                    <li><strong>Несколько документов:</strong><br>3fa85f64-5717-4562-b3fc-2c963f66afa6, 61f656e0-0a86-4ec2-bd43-232499f7ad66</li>
                    <li><strong>Использование переменной БП:</strong><br>{=Document:PAYMENT_ID}</li>
                </ul>
            </div>
        </td>
    </tr>
    
    <!-- Информация о статусах -->
    <tr>
        <td colspan="2">
            <div style="background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 10px 0;">
                <h4><?= GetMessage("TBANK_STATUS_STATUSES_INFO") ?></h4>
                <ul>
                    <li>DRAFT — черновик платежа, доступно изменение реквизитов;</li>
                    <li>DELETED — удален;</li>
                    <li>UNSIGNED — находится в процессе подписания;</li>
                    <li>SIGNED — полностью подписан и готов к отправке;</li>
                    <li>EXECUTED — исполнен процессинговой системой;</li>
                    <li>CANCELLED — отменен клиентом;</li>
                    <li>DECLINED — отклонено процессинговой системой.</li>
                </ul>
            </div>
        </td>
    </tr>
    
    <!-- Возвращаемые значения -->
    <tr>
        <td colspan="2">
            <div style="background-color: #e7f3ff; padding: 10px; border-left: 4px solid #2196f3; margin: 10px 0;">
                <h4><?= GetMessage("TBANK_STATUS_RETURN_INFO") ?></h4>
                <ul>
                    <li><strong>ExecutedPayments</strong> - <?= GetMessage("TBANK_STATUS_RETURN_EXECUTED") ?></li>
                </ul>
            </div>
        </td>
    </tr>
    
    <!-- Справочная информация -->
    <tr>
        <td colspan="2">
            <div style="background-color: #f0f8ff; padding: 10px; border-left: 4px solid #1e90ff; margin: 10px 0;">
                <h4><?= GetMessage("TBANK_STATUS_HELP_TITLE") ?></h4>
                <ul>
                    <li><?= GetMessage("TBANK_STATUS_HELP_1") ?></li>
                    <li><?= GetMessage("TBANK_STATUS_HELP_2") ?></li>
                    <li><?= GetMessage("TBANK_STATUS_HELP_3") ?></li>
                    <li><?= GetMessage("TBANK_STATUS_HELP_4") ?></li>
                </ul>
            </div>
        </td>
    </tr>
    
    <tr>
        <td colspan="2">
            <small style="color: red;"><?= GetMessage("TBANK_STATUS_REQUIRED_FIELDS") ?></small>
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
    
    /* Стили для различных типов уведомлений */
    div[style*="background-color: #f0f8ff"] {
        border-radius: 5px;
    }
    
    div[style*="background-color: #fff3cd"] {
        border-radius: 5px;
    }
    
    div[style*="background-color: #e7f3ff"] {
        border-radius: 5px;
    }
    
    ul {
        margin: 5px 0;
        padding-left: 20px;
    }
    
    li {
        margin: 5px 0;
    }
</style>