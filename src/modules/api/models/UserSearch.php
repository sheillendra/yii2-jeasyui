<?php

namespace sheillendra\jeasyui\modules\api\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form of `common\models\UserExt`.
 */
class UserSearch extends User
{

    public $roles;
    public $nama_lengkap;

    public function fields()
    {
        $fields = parent::fields();
        $fields['roles'] = 'roles';
        $fields['nama_lengkap'] = 'nama_lengkap';
        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [
                [
                    'username', 'auth_key', 'password_hash',
                    'password_reset_token', 'email', 'verification_token',
                    'roles', 'nama_lengkap',
                ],
                'safe'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this::find()
            ->alias('t0')
            ->select([
                't0.*',
                't1.roles',
                't1.max_level',
                //'t2.nama AS nama_lengkap'
                't0.username AS nama_lengkap'
            ])
            ->leftJoin($this->authAssigmentWithRoles() . ' t1', 't1.user_id = t0.id')
            // ->leftJoin('{{%personel}} t2', 't2.user_id=t0.id')
        ;

        if (Yii::$app->user->can($this::SUPERADMIN_ROLE)) {
            if (Yii::$app->user->id > 1) {
                $query->where(['>', 't0.id', 2]);
            }
        } else {
            $query->where(['>', 't1.max_level', Yii::$app->user->identity->authMaxLevel])
                ->orWhere(['t1.roles' => null]);
        }

        if (Yii::$app->user->can($this::ADMIN_ROLE)) {
        } else {
            // if (Yii::$app->user->can($this::ADMIN_ROLE_OPD)) {
            //     $query->andWhere(['t2.opd_id' => Yii::$app->user->identity->opdAdmin]);
            // }
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            't0.status' => $this->status,
            't0.created_at' => $this->created_at,
            't0.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 't0.username', $this->username])
            //->andFilterWhere(['ilike', 'auth_key', $this->auth_key])
            //->andFilterWhere(['ilike', 'password_hash', $this->password_hash])
            //->andFilterWhere(['ilike', 'password_reset_token', $this->password_reset_token])
            //->andFilterWhere(['ilike', 'email', $this->email])
            //->andFilterWhere(['ilike', 'verification_token', $this->verification_token])
            ->andFilterWhere(['ilike', 'roles', $this->roles])
            //->andFilterWhere(['or', ['ilike', 't2.nama_tanpa_gelar', $this->nama_lengkap], ['ilike', 't3.nama_tanpa_gelar', $this->nama_lengkap]])
            ;
        return $dataProvider;
    }
}
