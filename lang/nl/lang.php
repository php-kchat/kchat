<?php
$translations = [
    'create-widget' => 'Widget maken',
    'widget-title' => 'Widgettitel',
    'widget-department' => 'Widgetafdeling',
    'widget-language' => 'Widgettaal',
    'widget-icon' => 'Widgetpictogram',
    'widget-color' => 'Widgetkleur',
    'widget-position' => 'Widgetpositie',
    'embed-code' => 'Widget-JS',
    'chat-widget-setting' => 'Widgetinstellingen',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
