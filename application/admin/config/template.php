<?php
/**
 * 模板配置
 */
return[
    // 模板替换输出
    'tpl_replace_string'  =>  [
        '__CSS__'=>'/static/admin/css',
        '__JS__' => '/static/admin/js',
        '__LIB__' => '/static/admin/lib'
    ],
    //模板缓存,开启以后配置替换输出可能不生效
    'tpl_cache'    => true,
];
