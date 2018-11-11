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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'estimate_id'], 'integer'],
            [['date'], 'safe'],
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
        $query->from(['smeta' => \common\models\Smeta::tableName()]);
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
        if ($this->estimate_id) $query->andFilterWhere([
            'smeta.estimate_id' => $this->estimate_id,
        ]);

        return $dataProvider;
    }
}
