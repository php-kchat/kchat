<?php
$translations = [
    'create-widget' => 'Widget létrehozása',
    'widget-title' => 'Widget címe',
    'widget-department' => 'Widget részlege',
    'widget-language' => 'Widget nyelve',
    'widget-icon' => 'Widget ikonja',
    'widget-color' => 'Widget színe',
    'widget-position' => 'Widget pozíciója',
    'embed-code' => 'Widget JS',
    'chat-widget-setting' => 'Widget beállításai',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
