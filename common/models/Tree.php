<?

namespace common\models;

use kartik\tree\models\TreeTrait;


class Tree extends \yii\db\ActiveRecord
{
    use TreeTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_tree';
    }

}

