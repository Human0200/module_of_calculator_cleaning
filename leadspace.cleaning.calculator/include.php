<?php

use Bitrix\Main\Loader;
Loader::registerAutoLoadClasses('leadspace.cleaning.calculator', [
    'LeadSpace\Cleaning\UserField\Pollution\PollutionDegreeField' => 'lib/UserField/PollutionDegreeField.php',
    'LeadSpace\Tabs\Calculator\CalculatorTab' => 'lib/customTab.php',
]);