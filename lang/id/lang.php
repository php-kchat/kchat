<?php
$translations = [
    'create-widget' => 'Buat widget',
    'widget-title' => 'Judul widget',
    'widget-department' => 'Departemen widget',
    'widget-language' => 'Bahasa widget',
    'widget-icon' => 'Ikon widget',
    'widget-color' => 'Warna widget',
    'widget-position' => 'Posisi widget',
    'embed-code' => 'JS widget',
    'chat-widget-setting' => 'Pengaturan widget',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
