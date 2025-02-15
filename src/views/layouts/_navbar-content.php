<?php

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this \yii\web\View */

?>
<div id="navbar">
    <div id="layer-btn"></div>
    <div id="layer-menu" style="display: none;">
        <div data-options="name:\'app1\',iconCls:\'fa-solid fa-check\'">APP 1</div>
        <div data-options="name:\'app2\'">APP 2</div>
        <div data-options="name:\'app3\'">APP 3</div>
    </div>
    <div class="panel-title" style="display: inline;margin-right: 100px;height:45px">CORPORATE</div>
    <div style="display: inline;width: 300px;"><input id="navbar-search-box" style="display: none;"></input></div>
    <div id="north-right-nav">
        <div id="mm" style="width:120px;display:none;">
            <div data-options="name:\'all\',iconCls:\'fa-solid fa-check\'">All</div>
            <div data-options="name:\'post\'">Post</div>
            <div data-options="name:\'user\'">User</div>
        </div>
        <div id="notification-btn"></div>
        <a id="north-user-menu-btn"></a>
        <div id="north-user-menu">
            
        </div>
    </div>
</div>

<?php

$userMenuItem = [];

//custom from here
$profileUrl = Url::to(['/jeasyui/profile']);

$userMenuItem[] = [
    'id' => 'nav-profile',
    'text' => 'Profile',
    'iconCls' => 'fa-solid fa-address-card',
    'attributes' => [
        'url' => $profileUrl,
    ],
    'onclick' => new JsExpression(
        <<<JS
        function (e) {
            yii.easyui.createTab({
                title: 'Profile',
                href: '{$profileUrl}',
                iconCls: 'fa-solid fa-address-card'
            }, 'nav-profile');
            e.stopPropagation();
        }
JS
    )
];

$userMenuItem[] = ['separator' => 1];
$userMenuItem[] = [
    'text' => 'Theme',
    'iconCls' => 'fa-solid fa-palette',
];

$templateThemeOnClick = <<<JS
    function (e) {
        yii.easyui.cookie.delete('jeasyui-theme');
        yii.easyui.cookie.set('jeasyui-theme', '{thema}');
        window.location.reload(false);
        e.stopPropagation();
    }
JS;

$defaultTheme = \sheillendra\jeasyui\components\helpers\AssetHelper::defaultTheme('{theme}');
foreach (\sheillendra\jeasyui\components\helpers\AssetHelper::THEMES as $k=>$v){
    $userMenuItem[] = [
        'parent' => 'Theme',
        'text' => ucfirst($v),
        'iconCls' => $defaultTheme == $v? 'fa-solid fa-check':'',
        'onclick' => new JsExpression(strtr($templateThemeOnClick, ['{thema}' => $v]))
    ];
}

$userMenuItem[] = ['separator' => 1];

$userMenuItem[] = [
    'text' => 'Logout',
    'iconCls' => 'fa-solid fa-right-from-bracket',
    'onclick' => new JsExpression(
        <<<JS
        function (e) {
            yii.handleAction($('<a href="{$this->params['logoutUrl']}" data-method="post"></a>'));
            e.stopPropagation();
        }
JS
    )
];

$northUserMenuItem = Json::encode($userMenuItem);

$this->params['baseUrl'] = $_ENV['URL_BASE'];

$this->registerJs(
    <<<JS
    var northUserMenuItem = {$northUserMenuItem};
    yii.easyui.afterInitMainLayout.push(()=>{

        $('#layer-btn').menubutton({
            iconCls: 'fa-solid fa-layer-group',
            plain: true,
            size: 'large',
            menu: '#layer-menu',
            border: false,
            showEvent: 'mousedown',
            hasDownArrow: false
        });

        $('#notification-drawer').drawer();
        $('#notification-btn').linkbutton({
            iconCls: 'fa-solid fa-bell',
            plain: true,
            size: 'large',
            border: false,
            onClick: function(){
                $('#notification-drawer').drawer('expand');
            }
        });

        $('#navbar-search-box').searchbox({
            width: 400,
            searcher:function(value,name){
                alert(value + "," + name)
            },
            menu:'#mm',
            prompt:'Search'
        });

        yii.easyui.northUserMenu = $('#north-user-menu').menu({});
        var parentItem = {};
        
        $.each(northUserMenuItem, function (k, v) {
            if (v.parent !== undefined) {
                if (parentItem[v.parent] === undefined) {
                    parentItem[v.parent] = yii.easyui.northUserMenu.menu('findItem', v.parent);
                }
                if (parentItem[v.parent]) {
                    v.parent = parentItem[v.parent].target;
                } else {
                    return false;
                }
            }
            yii.easyui.northUserMenu.menu('appendItem', v);
        });

        $('#north-user-menu-btn').menubutton({
            text: yii.easyui.username,
            iconCls: 'fa-solid fa-user',
            menu: yii.easyui.northUserMenu,
            menuAlign: 'right',
            showEvent: 'mousedown',
            size: 'large',
            hasDownArrow: false
        });

    });
JS
);