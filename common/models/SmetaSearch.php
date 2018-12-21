<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Smeta;

/**
 * SmetaSearch represents the model behind the search form of `common\models\Smeta`.
 */
class SmetaSearch extends Smeta
{

    public $show_copy;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'user_id', 'show_copy'], 'integer'],
            [['name'], 'string'],
            [['created_at','updated_at'], 'safe'],
            [['name'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Smeta::find();
        $query->from(['smeta' => Smeta::tableName()]);
        $query->joinWith('user');
        $query->joinWith('estimate');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->smeta_id) $query->andFilterWhere([
            'smeta.smeta_id' => $this->smeta_id,
        ]);

        if ($this->name) $query->andFilterWhere([
            'like','smeta.name',$this->name,
        ]);
        if (!$this->show_copy) $query->andWhere([
            'IS','smeta.history_of',NULL
        ]);
        if (Yii::$app->user->can('admin')) {
        if ($this->user_id) $query->andFilterWhere([
            'smeta.user_id' => $this->user_id,
        ]);
        } else {
            $user_id = Yii::$app->user->identity->id;
            $query->andFilterWhere([
                'smeta.user_id' => $user_id,
            ]);
        }


        return $dataProvider;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(),['show_copy' => 'ПОказывать копии']);
    }
}
