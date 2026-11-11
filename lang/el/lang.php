<?php
$translations = [
    'create-widget' => 'Δημιουργία widget',
    'widget-title' => 'Τίτλος widget',
    'widget-department' => 'Τμήμα widget',
    'widget-language' => 'Γλώσσα widget',
    'widget-icon' => 'Εικονίδιο widget',
    'widget-color' => 'Χρώμα widget',
    'widget-position' => 'Θέση widget',
    'embed-code' => 'JS widget',
    'chat-widget-setting' => 'Ρυθμίσεις widget',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
