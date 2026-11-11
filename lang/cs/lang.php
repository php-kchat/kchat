<?php
$translations = [
    'create-widget' => 'Vytvořit widget',
    'widget-title' => 'Název widgetu',
    'widget-department' => 'Oddělení widgetu',
    'widget-language' => 'Jazyk widgetu',
    'widget-icon' => 'Ikona widgetu',
    'widget-color' => 'Barva widgetu',
    'widget-position' => 'Pozice widgetu',
    'embed-code' => 'JS widgetu',
    'chat-widget-setting' => 'Nastavení widgetu',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
