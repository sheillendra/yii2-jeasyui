<?php

namespace sheillendra\jeasyui\models;

use Yii;
use yii\base\Model;
use common\models\UserExt;

/**
 * Login form
 */
class ChangePasswordForm extends Model {

    public $oldPassword;
    public $newPassword;
    public $repeatPassword;
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['oldPassword', 'newPassword', 'repeatPassword'], 'required'],
            [['oldPassword', 'newPassword', 'repeatPassword'], 'string', 'min' => 6],
            // password is validated by validatePassword()
            ['oldPassword', 'validatePassword'],
            [
                'newPassword',
                'compare',
                'compareAttribute' => 'oldPassword',
                'operator' => '!==',
                'message' => 'Password baru tidak boleh sama dengan password lama.'
            ],
            [
                'repeatPassword',
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => 'Pengulangan password baru tidak benar, harus sama persis.'
            ],
        ];
    }

    public function attributeLabels() {
        return [
            'oldPassword' => 'Password saat ini',
            'newPassword' => 'Password baru',
            'repeatPassword' => 'Ulangi password baru',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->oldPassword)) {
                $this->addError($attribute, 'Incorrect old password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function change() {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->setPassword($this->newPassword);
            $user->generateAuthKey();
            if ($user->save(false)) {
                // Yii::$app->db->createCommand()
                //         ->delete('{{%session}}', ['user_id' => $user->id])
                //         ->execute();
                return true;
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser() {
        if ($this->_user === null) {
            $this->_user = UserExt::findOne(Yii::$app->user->id);
        }

        return $this->_user;
    }

}
