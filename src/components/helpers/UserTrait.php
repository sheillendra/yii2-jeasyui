<?php

namespace sheillendra\jeasyui\components\helpers;

use Yii;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;

trait UserTrait
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    /// ================ START DEFAULT USER FROM ADVANCED TEMPLATE ===================================

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    // public static function findIdentityByAccessToken($token, $type = null)
    // {
    //     throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    // }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /// ================ END DEFAULT USER FROM ADVANCED TEMPLATE ===================================


    /// ================ START CUSTOM =====================================================

    const STATUS_LABEL = [
        self::STATUS_DELETED => 'Deleted',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_ACTIVE => 'Active',
    ];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * Soft Delete
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $this->status = self::STATUS_DELETED;
        $this->save();
        return false;
    }

    public function getToken()
    {
        //return JWT::encode($this->toArray(), Yii::$app->params['jwtKey']);
        return  JWT::encode([
            'id' => $this->id,
            'createdTime' => time(),
            //'ttl' => time() + (60 * 60)
        ], Yii::$app->params['jwtKey'], 'HS256');
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $key = new Key(Yii::$app->params['jwtKey'], 'HS256');
        $data = JWT::decode($token, $key);
        if (!isset($data->id) && $data->id) {
            return null;
        }
        return static::findOne(['id' => $data->id, 'status' => self::STATUS_ACTIVE]);
        //return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public function assign($roleName)
    {
        $result = [
            'success' => 1,
            'message' => 'Assign role is success'
        ];

        try {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($roleName);
            $auth->assign($role, $this->id);
        } catch (\Exception $ex) {
            $result['success'] = 0;
            $result['message'] = $ex->getMessage();
        }
        return $result;
    }

    public function revoke($roleName)
    {
        $result = [
            'success' => 1,
            'message' => 'Revoke role is success'
        ];

        try {
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($roleName);
            $auth->revoke($role, $this->id);
        } catch (\Exception $ex) {
            $result['success'] = 0;
            $result['message'] = $ex->getMessage();
        }
        return $result;
    }

    /**
     * RBAC for specific user
     * 
     * @param string $roleName
     * @return boolean
     */
    public function can($roleName)
    {
        $auth = Yii::$app->getAuthManager();
        $permissions = $auth->getPermissionsByUser($this->id);
        if (isset($permissions[$roleName])) {
            return true;
        }

        $roles = $auth->getRolesByUser($this->id);
        return isset($roles[$roleName]);
    }

    /**
     * 
     * @return integer
     */
    public function getAuthMaxLevel()
    {
        return (int) $this->getDb()->createCommand(
            <<<SQL
            SELECT
                MIN(tt1.level) as max_level
            FROM {{%auth_assignment}} tt0
            LEFT JOIN {$this->authItemWithLevel} tt1 
                ON tt0.item_name = tt1.name
            WHERE tt0.user_id = :uid
            GROUP BY tt0.user_id
SQL,
            [':uid' => (string)$this->id]
        )->queryScalar();
    }

    /**
     * 
     * @return string
     */
    public function getAllRoles($id = null)
    {
        if ($id === null) {
            $id = $this->id;
        }

        if ($this->_allRoles === null) {
            $this->_allRoles = $this->getDb()->createCommand(
                <<<SQL
    SELECT
        STRING_AGG(tt0.item_name, ', ' ORDER BY tt1.level)
    FROM {{%auth_assignment}} tt0
    LEFT JOIN {$this->authItemWithLevel} tt1 
        ON tt0.item_name = tt1.name
    WHERE tt0.user_id = :uid
    GROUP BY tt0.user_id
SQL,
                [':uid' => (string)$this->id]
            )->queryScalar();
        }
        return $this->_allRoles;
    }

    public function getAllRolesWithLabel()
    {
        return strtr($this->allRoles, $this->getRoleLabel());
    }

    /**
     *
     * @var string SQL 
     */
    public $authItemWithLevel = <<<SQL
        (
            SELECT 
                *,
                ENCODE(SUBSTRING(
                  "data",
                  position('s:5:"level";i:' in "data") + 14,
                  position(';' in SUBSTRING(
                  "data",
                  position('s:5:"level";i:' in "data") + 14, 4)) - 1
                ), 'escape')::int "level" 
            FROM
                {{%auth_item}}
            WHERE "type" = 1
            ORDER BY "level"
        )
SQL;

    /**
     *
     * @var type 
     */
    public function authAssigmentWithRoles()
    {
        return <<<SQL
        (
            SELECT
                tt0.user_id::int,
                STRING_AGG(tt0.item_name, ', ' ORDER BY tt1.level) roles,
                MIN(tt1.level) as max_level
            FROM {{%auth_assignment}} tt0
            LEFT JOIN {$this->authItemWithLevel} tt1 
                ON tt0.item_name = tt1.name
            GROUP BY tt0.user_id
        )
SQL;
    }

    public function getAuthItemCanManage()
    {
        $result = [];
        if ($this->username === 'superadmin') {
            $result = $this->db->createCommand(
                <<<SQL
                SELECT t0.name, t0.description, t0.level FROM ($this->authItemWithLevel) t0
    SQL
            )->queryAll();
        } else {
            $result = $this->db->createCommand(
                <<<SQL
                SELECT t0.name, t0.description, t0.level FROM ($this->authItemWithLevel) t0
                WHERE t0.level > $this->authMaxLevel
    SQL
            )->queryAll();
        }

        foreach ($result as $k => $v) {
            $roleName = 'self::' . strtoupper(Inflector::camel2id(Inflector::id2camel($v['name']), '_')) . '_ROLE';
            if (!defined($roleName)) {
                unset($result[$k]);
            }
        }
        return $result;
    }

    public function resetRbac($appName = 'Application')
    {
        $result = [
            'success' => 1,
            'message' => "Reset RBAC {$appName} is success"
        ];
        $authManager = Yii::$app->authManager;
        $roles = [];
        $permissions = [];
        foreach ($this->defaultRolesPermissions as $roleName => $v) {
            if (!isset($roles[$roleName])) {
                $roles[$roleName] = $authManager->getRole($roleName);
                if (!isset($roles[$roleName])) {
                    $roles[$roleName] = $authManager->createRole($roleName);
                    $roles[$roleName]->description = ucwords($roleName);
                    $roles[$roleName]->data = ['level' => $this->getRoleLevel($roleName)];
                    $authManager->add($roles[$roleName]);
                }
            }

            if (isset($v['permissions'])) {
                foreach ($v['permissions'] as $permissionName) {
                    if (!isset($permissions[$permissionName])) {
                        $permissions[$permissionName] = $authManager->getPermission($permissionName);
                        if (!isset($permissions[$permissionName])) {
                            $permissions[$permissionName] = $authManager->createPermission($permissionName);
                            $authManager->add($permissions[$permissionName]);
                        }
                    }

                    $permissionAsRoleChild = $authManager->getPermissionsByRole($roleName);
                    if (!isset($permissionAsRoleChild[$permissionName])) {
                        $authManager->addChild($roles[$roleName], $permissions[$permissionName]);
                    }
                }
            }

            if (isset($v['roles'])) {
                foreach ($v['roles'] as $childRoleName) {
                    if (!isset($roles[$childRoleName])) {
                        $roles[$childRoleName] = $authManager->getRole($childRoleName);
                        if (!isset($roles[$childRoleName])) {
                            $roles[$childRoleName] = $authManager->createRole($childRoleName);
                            $roles[$childRoleName]->description = ucwords($childRoleName);
                            $roles[$childRoleName]->data = ['level' => $this->getRoleLevel($childRoleName)];
                            $authManager->add($roles[$childRoleName]);
                        }
                    }

                    if ($authManager->hasChild($roles[$roleName], $roles[$childRoleName]) === false) {
                        $authManager->addChild($roles[$roleName], $roles[$childRoleName]);
                    }
                }
            }
        }
        return $result;
    }
}
