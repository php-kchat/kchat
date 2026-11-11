<?php
$translations = [
    'create-widget' => 'Luo widget',
    'widget-title' => 'Widgetin otsikko',
    'widget-department' => 'Widgetin osasto',
    'widget-language' => 'Widgetin kieli',
    'widget-icon' => 'Widgetin kuvake',
    'widget-color' => 'Widgetin väri',
    'widget-position' => 'Widgetin sijainti',
    'embed-code' => 'Widgetin JS',
    'chat-widget-setting' => 'Widgetin asetukset',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
