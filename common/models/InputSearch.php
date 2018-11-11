<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Input;

/**
 * InputSearch represents the model behind the search form of `common\models\Input`.
 */
class InputSearch extends Input
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['input_id', 'estimate_id', 'stage_id', 'type', 'validation_rule_id', 'multiple'], 'integer'],
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
        $query = Input::find();

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
        $query->andFilterWhere([
            'input_id' => $this->input_id,
            'estimate_id' => $this->estimate_id,
            'stage_id' => $this->stage_id,
            'type' => $this->type,
            'validation_rule_id' => $this->validation_rule_id,
            'multiple' => $this->multiple,
        ]);

        return $dataProvider;
    }
}
