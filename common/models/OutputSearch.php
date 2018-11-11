<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Output;

/**
 * OutputSearch represents the model behind the search form of `common\models\Output`.
 */
class OutputSearch extends Output
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['output_id', 'estimate_id', 'stage_id', 'width', 'priority'], 'integer'],
            [['name', 'formula'], 'safe'],
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
        $query = Output::find();

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
            'output_id' => $this->output_id,
            'estimate_id' => $this->estimate_id,
            'stage_id' => $this->stage_id,
            'width' => $this->width,
            'priority' => $this->priority,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'formula', $this->formula]);

        return $dataProvider;
    }
}
