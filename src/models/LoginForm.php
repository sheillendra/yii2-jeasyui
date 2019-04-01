<?php

namespace sheillendra\jeasyui\models;

class LoginForm extends \common\models\LoginForm {
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        return parent::getUser();
    }
}
