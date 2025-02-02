<?php

namespace sheillendra\jeasyui\components\helpers;

class AssetHelper {
    const THEMES = [
        'black',
        'bootstrap',
        'default',
        'gray',
        'material',
        'material-blue',
        'material-teal',
        'metro',
    ];

    public static function defaultTheme($format = 'themes/{theme}/easyui.css'){
        $themeCookies = filter_input(INPUT_COOKIE, 'jeasyui-theme');
        
        if ($themeCookies && in_array($themeCookies, static::THEMES)) {
            $theme = $themeCookies;
        } else {
            $theme = 'default';
        }

        return strtr($format, ['{theme}' => $theme]);
    }
}