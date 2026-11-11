<?php
$translations = [
    'create-widget' => 'Opret widget',
    'widget-title' => 'Widgettitel',
    'widget-department' => 'Widgetafdeling',
    'widget-language' => 'Widgetsprog',
    'widget-icon' => 'Widgetikon',
    'widget-color' => 'Widgetfarve',
    'widget-position' => 'Widgetplacering',
    'embed-code' => 'Widget-JS',
    'chat-widget-setting' => 'Widgetindstillinger',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
