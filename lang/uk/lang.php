<?php
$translations = [
    'create-widget' => 'Створити віджет',
    'widget-title' => 'Назва віджета',
    'widget-department' => 'Відділ віджета',
    'widget-language' => 'Мова віджета',
    'widget-icon' => 'Значок віджета',
    'widget-color' => 'Колір віджета',
    'widget-position' => 'Положення віджета',
    'embed-code' => 'JS віджета',
    'chat-widget-setting' => 'Налаштування віджета',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
