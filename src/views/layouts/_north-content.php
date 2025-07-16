<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<div style="padding-top: 5px">
    <div id="toggle-sidebar"></div>
    <div id="tools"></div>
    <div id="tools-menu"></div>
    <div id="rbac"></div>
    <div id="rbac-menu"></div>
</div>

<?php
$this->render('@app/views/layouts/_north-content-js.php');
