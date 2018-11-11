<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Material;

/**
 * MaterialSearch represents the model behind the search form of `common\models\Material`.
 */
class MaterialSearch extends Material
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'complex_of_works', 'measure', 'count', 'price', 'cost', 'check', 'product_type', 'material_type', 'sg_sht', 'type_cost', 'self_cost'], 'integer'],
            [['articul', 'name', 'manufacturer', 'articul_man', 'link_to_numenclature', 'check1', 'r', 'name_station_bux', 'station_code', 'name_short', 'link'], 'safe'],
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
        $query = Material::find();

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
            'complex_of_works' => $this->complex_of_works,
            'measure' => $this->measure,
            'count' => $this->count,
            'price' => $this->price,
            'cost' => $this->cost,
            'check' => $this->check,
            'product_type' => $this->product_type,
            'material_type' => $this->material_type,
            'sg_sht' => $this->sg_sht,
            'type_cost' => $this->type_cost,
            'self_cost' => $this->self_cost,
        ]);

        $query->andFilterWhere(['like', 'articul', $this->articul])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'manufacturer', $this->manufacturer])
            ->andFilterWhere(['like', 'articul_man', $this->articul_man])
            ->andFilterWhere(['like', 'link_to_numenclature', $this->link_to_numenclature])
            ->andFilterWhere(['like', 'check1', $this->check1])
            ->andFilterWhere(['like', 'r', $this->r])
            ->andFilterWhere(['like', 'name_station_bux', $this->name_station_bux])
            ->andFilterWhere(['like', 'station_code', $this->station_code])
            ->andFilterWhere(['like', 'name_short', $this->name_short])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
