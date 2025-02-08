<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<div id="left-rail">
    <div id="calendar"></div>
</div>

<?php
$this->registerJs(
    <<<JS
    yii.easyui.afterInit.push(()=>{
        var calendarLeftRail = $('#calendar').linkbutton({
            iconCls: 'fa-solid fa-calendar',
            plain: true,
            size: 'large',
        });

        $('#left-rail').append(calendarLeftRail);

        var peopleLeftRail = $('<div></div>').linkbutton({
            iconCls: 'fa-solid fa-users',
            plain: true,
            size: 'large',
        });

        $('#left-rail').append(peopleLeftRail);

        var groupLeftRail = $('<div></div>').linkbutton({
            iconCls: 'fa-solid fa-people-group',
            plain: true,
            size: 'large',
        });

        $('#left-rail').append(groupLeftRail);
        
    });
JS
);
