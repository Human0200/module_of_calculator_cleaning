<?php
namespace LeadSpace\Tabs\Calculator;

use Bitrix\Main\Loader;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

Loader::includeModule('crm');

class CalculatorTab
{
    protected \CCrmPerms $userPermissions;

    public function __construct()
    {
        $this->userPermissions = \CCrmPerms::GetCurrentUserPermissions();
    }

    public static function onEntityDetailsTabsInitialized(\Bitrix\Main\Event $event)
    {
       // file_put_contents(__DIR__ . '/log.txt', print_r($event->getParameters(), true));
        $entityID = $event->getParameter('entityID');
        $entityTypeID = $event->getParameter('entityTypeID');
        $tabs = $event->getParameter('tabs');

        // Обрабатываем только сделки (DEAL)
        if ($entityTypeID == \CCrmOwnerType::Deal) {
            $manager = new self();
            $tabs = $manager->getActualDealTabs($tabs, $entityID);
        }

        return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, [
            'tabs' => $tabs,
        ]);
    }

    private function getActualDealTabs(array $tabs, int $dealId): array
    {
        $canUpdateDeal = \CCrmDeal::CheckUpdatePermission($dealId, $this->userPermissions);

        if ($canUpdateDeal) {
            $tabs[] = [
                'id' => 'calculator_tab',
                'name' => 'Калькулятор',
                'enabled' => !empty($dealId),
                'loader' => [
                    'serviceUrl' => '/local/ajax/calculator_tab_content.php?&site=' . SITE_ID . '&' . bitrix_sessid_get(),
                    'componentData' => [
                        'template' => '',
                        'params' => [
                            'ENTITY_TYPE_ID' => \CCrmOwnerType::Deal,
                            'ENTITY_ID' => $dealId
                        ]
                    ]
                ]
            ];
        }

        return $tabs;
    }
}