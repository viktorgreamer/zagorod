<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EstimateStage;

/**
 * EstimateStageSearch represents the model behind the search form of `common\models\EstimateStage`.
 */
class EstimateStageSearch extends EstimateStage
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stage_id', 'estimate_id', 'priority'], 'integer'],
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
        $query = EstimateStage::find();

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
            'stage_id' => $this->stage_id,
            'estimate_id' => $this->estimate_id,
            'priority' => $this->priority,
        ]);

        return $dataProvider;
    }
}
