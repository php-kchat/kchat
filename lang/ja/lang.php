<?php
$translations = [
    'create-widget' => 'ウィジェットを作成',
    'widget-title' => 'ウィジェットのタイトル',
    'widget-department' => 'ウィジェットの部署',
    'widget-language' => 'ウィジェットの言語',
    'widget-icon' => 'ウィジェットアイコン',
    'widget-color' => 'ウィジェットの色',
    'widget-position' => 'ウィジェットの位置',
    'embed-code' => 'ウィジェット JS',
    'chat-widget-setting' => 'ウィジェット設定',
];

return array_merge(require dirname(__DIR__) . '/en/lang.php', $translations);
