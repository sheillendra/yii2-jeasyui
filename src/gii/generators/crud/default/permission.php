<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

echo "<?php\n" ?>

namespace <?= $generator->appName?>\models;

use common\models\Role;

class Permission extends Role
{
<?php

$apiControllerPath = Yii::getAlias('@' . str_replace('\\', '/', ltrim($generator->apiName, '\\')) . '/controllers');
$firstId = null;
if (!is_dir($apiControllerPath)) {
    mkdir($apiControllerPath, 0777, true);
}
$files = scandir($apiControllerPath);
if (!in_array($baseControllerName . '.php', $files)) {
    $files[] = $baseControllerName . '.php';
}

$constForSuperadmin = '';
foreach ($files as $file) {
    if (($file === $baseControllerName . '.php') || is_file($apiControllerPath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $baseName  = str_replace('Controller.php', '', $file);
        $id = Inflector::camel2id($baseName);
        $constId = strtoupper(Inflector::camel2id($baseName, '_'));
?>
    const CREATE_<?= $constId ?>_PERMISSION = 'create-<?= $id ?>';
    const READ_<?= $constId ?>_PERMISSION = 'read-<?= $id ?>';
    const UPDATE_<?= $constId ?>_PERMISSION = 'update-<?= $id ?>';
    const DELETE_<?= $constId ?>_PERMISSION = 'delete-<?= $id ?>';

<?php
        $constForSuperadmin .= "\n" . '                    self::CREATE_' . $constId . '_PERMISSION,';
        $constForSuperadmin .= "\n" . '                    self::READ_' . $constId . '_PERMISSION,';
        $constForSuperadmin .= "\n" . '                    self::UPDATE_' . $constId . '_PERMISSION,';
        $constForSuperadmin .= "\n" . '                    self::DELETE_' . $constId . '_PERMISSION,';
        $constForSuperadmin .= "\n";
    }
} 

?>
    public function getDefaultRolesPermissions()
    {
        return [
            self::SUPERADMIN_ROLE => [
                'permissions' => [<?= $constForSuperadmin?>
                ],
                'roles' => []
            ],
        ];
    }
}