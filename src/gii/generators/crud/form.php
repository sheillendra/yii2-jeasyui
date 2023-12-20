<?php

echo $form->field($generator, 'modelClass');
echo $form->field($generator, 'appName');
echo $form->field($generator, 'apiName');
// echo $form->field($generator, 'viewPath');
// echo $form->field($generator, 'baseControllerClass');
// echo $form->field($generator, 'indexWidgetType')->dropDownList([
//     'grid' => 'datagrid'
// ]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'messageCategory');
echo $form->errorSummary($generator);