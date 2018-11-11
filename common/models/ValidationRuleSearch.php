<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ValidationRule;

/**
 * ValidationRuleSearch represents the model behind the search form of `common\models\ValidationRule`.
 */
class ValidationRuleSearch extends ValidationRule
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'max', 'min', 'required'], 'integer'],
            [['name'], 'string'],
            [['preg_match'], 'safe'],
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
        $query = ValidationRule::find();

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
            'id' => $this->id,
            'type' => $this->type,
            'max' => $this->max,
            'min' => $this->min,
            'required' => $this->required,
        ]);

        $query->andFilterWhere(['like', 'preg_match', $this->preg_match]);
        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
