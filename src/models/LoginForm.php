<?php

namespace sheillendra\jeasyui\models;

use common\models\UserExt;
use Yii;

class LoginForm extends \common\models\LoginForm {

    private $_user;
    
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = UserExt::findByUsername($this->username);
        }
        
        return $this->_user;
    }
}