<tr>
  <td align="right" width="40%"><b><?= GetMessage("CREATEPROCESSACTIVITY_RECIPIENT") ?></b> :</td>
  <td width="60%">
    <?= CBPDocument::ShowParameterField("string", 'recipient', $arCurrentValues['recipient'], ['size' => '50']) ?>
  </td>
</tr>
