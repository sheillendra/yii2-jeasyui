<?php

namespace sheillendra\jeasyui\modules\api\controllers;

use Yii;
use yii\web\ServerErrorHttpException;
use sheillendra\jeasyui\components\rest\ActiveController;
use common\models\UserExt;
use sheillendra\jeasyui\models\SignupForm;

class UserController extends ActiveController
{

    public $modelClass = 'common\models\UserExt';

    public function actionResetPassword($id)
    {
        $result = [
            'message' => ''
        ];
        $model = $this->modelClass::findOne($id);
        $password = rand(111111, 999999);
        $model->setPassword($password);
        if ($model->save()) {
            $result['message'] = 'Reset password <kbd>' .
                $model->username . '</kbd> berhasil dengan password baru: <kbd>' .
                $password . '</kbd>';
        } else {
            throw new ServerErrorHttpException($model->getFirstErrors());
        }
        return $result;
    }

    public function actionAssign($id)
    {
        $result = [
            'success' => false,
            'message' => ''
        ];

        $model = $this->modelClass::findOne($id);
        if (!$model) {
            $result['message'] = 'User tidak ditemukan';
            return $result;
        }

        if (
            !Yii::$app->user->can(UserExt::ROLE_SUPERADMIN) &&
            $model->maxLevel <= Yii::$app->user->identity->maxLevel
        ) {
            $result['message'] = 'Anda tidak bisa mengatur user dengan level yang lebih tinggi';
            return $result;
        }

        $roleName = Yii::$app->request->post('role');
        if (empty($roleName)) {
            $result['message'] = 'Role tidak boleh kosong';
            return $result;
        }

        $auth = Yii::$app->authManager;
        $revoke = Yii::$app->request->post('revoke');
        if ($auth->getAssignment($roleName, $id)) {
            if (!$revoke) {
                $result['message'] = 'Role ini sudah dimiliki oleh user';
                return $result;
            }
        } else {
            if ($revoke) {
                $result['message'] = 'Role tidak dimiliki oleh user';
                return $result;
            }
        }

        $role = $auth->getRole($roleName);
        if ($role) {
            if ($revoke) {
                $auth->revoke($role, $model->id);
                $result['message'] = 'Revoke sukses';
            } else {
                $auth->assign($role, $model->id);
                $result['message'] = 'Assign sukses';
            }
            $result['success'] = true;
        } else {
            $result['message'] = 'Role tidak ditemukan';
        }
        return $result;
    }

    public function actionAllRoles()
    {
        return Yii::$app->user->identity->allRolesWithLabel;
        //return Yii::$app->user->identity->allRolesWithLabel;
    }

    /**
     * Signup action.
     *
     * @return string
     */
    public function actionSignupByAdmin()
    {
        $result = [
            'success' => false,
            'message' => ''
        ];
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = $model->signup();
            if ($user) {
                $result['success'] = true;
                $result['message'] = strtr('Create new user {username} success', ['{username}' => $user->username]);
            } else {
                $result['message'] = $model->getFirstErrors();
            }
        } else {
            $result['message'] = $model->getFirstErrors();
        }
        return $result;
    }
}
