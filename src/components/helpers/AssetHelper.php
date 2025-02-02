<?php

namespace sheillendra\jeasyui\components\helpers;

class AssetHelper {
    public static function defaultTheme($format = 'themes/{theme}/easyui.css'){
        $themeCookies = filter_input(INPUT_COOKIE, 'jeasyui-theme');
        $themes = [
            'black',
            'bootstrap',
            'default',
            'gray',
            'material',
            'material-blue',
            'material-teal',
            'metro',
        ];
        if ($themeCookies && in_array($themeCookies, $themes)) {
            $theme = $themeCookies;
        } else {
            $theme = 'default';
        }

        return strtr($format, ['{theme}' => $theme]);
    }
}