<?php
$translations = [
    'create-widget' => 'Creează widget',
    'widget-title' => 'Titlul widgetului',
    'widget-department' => 'Departamentul widgetului',
    'widget-language' => 'Limba widgetului',
    'widget-icon' => 'Pictograma widgetului',
    'widget-color' => 'Culoarea widgetului',
    'widget-position' => 'Poziția widgetului',
    'embed-code' => 'JS widget',
    'chat-widget-setting' => 'Setările widgetului',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
