<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[EstimateStage]].
 *
 * @see EstimateStage
 */
class EstimateStageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return EstimateStage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    public function active() {
        $this->andWhere(['status' => EstimateStage::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @return EstimateStage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
