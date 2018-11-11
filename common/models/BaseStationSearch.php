<?php

namespace common\models;

use backend\utils\D;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BaseStation;

/**
 * BaseStationSearch represents the model behind the search form of `common\models\BaseStation`.
 */
class BaseStationSearch extends BaseStation
{

    public $groups_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'measure', 'count', 'price', 'cost', 'people', 'fecal_nas', 'sp', 'deep', 'type_calculate_id', 'utepl', 'cement_manual_pac', 'cement_tech_pac', 'gasket'], 'integer'],
            [['articul', 'name', 'groups_id', 'mark', 'with_chasers'], 'safe'],
            [['performance', 'self_cost', 'montaj', 'pnr', 'rshm', 'yakor', 'length', 'width', 'height', 'water', 'sand_manual', 'sand_tech', 'cement_manual', 'cement_tech', 'pit_manual', 'pit_tech'], 'number'],
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
        $query = BaseStation::find();
        $query->from(['s' => BaseStation::tableName()]);
        $query->joinWith('calculateType as ct');
        // $query->joinWith('groupsId as groupsId');
        $query->joinWith('groups as groups');

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
            's.id' => $this->id,

            's.performance' => $this->performance,
            's.people' => $this->people,
            's.fecal_nas' => $this->fecal_nas,
            /*  's.sp' => $this->sp,
              // 'deep' => $this->deep,
              's.type_calculate_id' => $this->type_calculate_id,*/
            //'self_cost' => $this->self_cost,
            /* 'montaj' => $this->montaj,
             'pnr' => $this->pnr,
             'rshm' => $this->rshm,
             'yakor' => $this->yakor,
             'length' => $this->length,
             'width' => $this->width,
             'height' => $this->height,
             'utepl' => $this->utepl,
             'water' => $this->water,
             'sand_manual' => $this->sand_manual,
             'sand_tech' => $this->sand_tech,
             'cement_manual' => $this->cement_manual,
             'cement_manual_pac' => $this->cement_manual_pac,
             'cement_tech' => $this->cement_tech,
             'cement_tech_pac' => $this->cement_tech_pac,
             'pit_manual' => $this->pit_manual,
             'pit_tech' => $this->pit_tech,
             'gasket' => $this->gasket,*/
        ]);

        if ($this->groups_id) {
            //  D::success("groups_id IS selected");
            $query->andWhere(['in', 'groups.group_id', $this->groups_id]);
        }

        if ($this->type_calculate_id) $query->andWhere(['s.type_calculate_id' => $this->type_calculate_id]);
        if ($this->sp) $query->andWhere(['s.sp' => $this->sp]);
        $query->andFilterWhere(['like', 's.articul', $this->articul])
            ->andFilterWhere(['like', 's.name', $this->name])
            ->andFilterWhere(['like', 's.mark', $this->mark]);
        // ->andFilterWhere(['like', 's.with_chasers', $this->with_chasers]);

        $query->groupBy('s.id');
        $query_clone = clone $query;
        $ids = $query_clone->select('s.id')->column();

        Yii::$app->session->set('base_station_ids', $ids);

        return $dataProvider;
    }
}
