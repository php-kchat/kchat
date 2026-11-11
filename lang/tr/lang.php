<?php
$translations = [
    'create-widget' => 'Widget oluştur',
    'widget-title' => 'Widget başlığı',
    'widget-department' => 'Widget departmanı',
    'widget-language' => 'Widget dili',
    'widget-icon' => 'Widget simgesi',
    'widget-color' => 'Widget rengi',
    'widget-position' => 'Widget konumu',
    'embed-code' => 'Widget JS',
    'chat-widget-setting' => 'Widget ayarları',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
