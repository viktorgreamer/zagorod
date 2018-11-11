<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Smeta]].
 *
 * @see Smeta
 */
class SmetaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Smeta[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Smeta|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
