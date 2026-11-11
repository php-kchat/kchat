<?php
$translations = [
    'create-widget' => 'إنشاء أداة',
    'widget-title' => 'عنوان الأداة',
    'widget-department' => 'قسم الأداة',
    'widget-language' => 'لغة الأداة',
    'widget-icon' => 'أيقونة الأداة',
    'widget-color' => 'لون الأداة',
    'widget-position' => 'موضع الأداة',
    'embed-code' => 'JS الأداة',
    'chat-widget-setting' => 'إعدادات الأداة',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
