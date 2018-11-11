<?php

namespace common\models;

use backend\utils\D;
use Yii;

/**
 * This is the model class for table "tracing".
 *
 * @property int $smeta_id Смета
 * @property int $parent_id Родитель
 * @property int $id id
 * @property string $name Название
 * @property int $diameter Диаметр
 * @property string $in_ground В земле
 * @property string $in_air Подвес
 * @property string $revision Ревизия
 * @property string $cascade Каскад
 * @property string $cascade_with_revision Каскад с ревизией
 * @property int $turn Поворот
 * @property string $rizer Подъем стояка
 * @property int $floor Проход пола/стены
 */
class Tracing extends \yii\db\ActiveRecord
{

    const FLOOR_WOOD = 1;
    const FLOOR_TILE = 2;
    const FLOOR_BRICK = 3;
    const FLOOR_AERATED_CONCRETE = 4;

    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'tracing';
    }

    public function getChilds()
    {
        return $this->hasMany(Tracing::className(), ['parent_id' => 'id']);
    }

    public function getParent()
    {
        return $this->hasMany(Tracing::className(), ['parent_id' => 'id']);
    }

    public function getCascades()
    {
        return $this->hasMany(TracingCascade::className(), ['tracing_id' => 'id']);
    }

    public function setCascades($items) {
        if ($items) {
            TracingCascade::deleteAll(['tracing_id' => $this->id]);
            foreach ($items['value'] as $cascade) {
                $tracingCascade = new TracingCascade(['tracing_id' => $this->id,'value' => $cascade]);
                $tracingCascade->save();
            }
        }
    }

    public function setRevisionCascades($items) {
        if ($items) {
            TracingRevisionCascade::deleteAll(['tracing_id' => $this->id]);
            foreach ($items['value'] as $cascade) {
                $tracingCascade = new TracingRevisionCascade(['tracing_id' => $this->id,'value' => $cascade]);
                $tracingCascade->save();
            }
        }
    }

    public function setRevisions($items) {
        if ($items) {
            TracingRevision::deleteAll(['tracing_id' => $this->id]);
            D::alert(" MODEL HAS REVISION");
            foreach ($items['value'] as $cascade) {
                $tracingCascade = new TracingRevision(['tracing_id' => $this->id,'value' => $cascade]);
               if (!$tracingCascade->save()) D::dump($tracingCascade->errors);
            }
        }
    }

    public function setTurns($items) {
        if ($items) {
            TracingTurn::deleteAll(['tracing_id' => $this->id]);
            foreach ($items['value'] as $cascade) {
                $tracingCascade = new TracingTurn(['tracing_id' => $this->id,'value' => $cascade]);
                $tracingCascade->save();
            }
        }
    }


    public function getRevisionCascades()
    {
        return $this->hasMany(TracingRevisionCascade::className(), ['tracing_id' => 'id']);
    }

    public function getRevisions()
    {
        return $this->hasMany(TracingRevision::className(), ['tracing_id' => 'id']);
    }
    public function getTurns()
    {
        return $this->hasMany(TracingTurn::className(), ['tracing_id' => 'id']);
    }
    public function getFloors()
    {
        return $this->hasMany(TracingFloor::className(), ['tracing_id' => 'id']);
    }

    public function setFloors($items) {
        if ($items) {
            TracingFloor::deleteAll(['tracing_id' => $this->id]);
            foreach ($items['value'] as $cascade) {
                $tracingCascade = new TracingFloor(['tracing_id' => $this->id,'value' => $cascade]);
                $tracingCascade->save();
            }
        }
    }




    public function tree()
    {

        /* @var $child Tracing */
        $array_tree = [];
        $node = [];
        if ($childs = $this->childs) {

            foreach ($childs as $key => $child) {
                // $node['color'] = "#000000";
                $node['id'] = $child->id;
                $node['text'] = $text = $child->name;
                $node['tag'] = 'tag';

                $node['nodes'] = $child->tree();
                array_push($array_tree, $node);
            }

            return $array_tree;
        }


    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['smeta_id', 'parent_id', 'diameter', 'floor','zaglushka'], 'integer'],
            [['name', 'diameter'], 'required'],
            [['in_ground', 'in_air', 'rizer'], 'number'],
            [['name'], 'string', 'max' => 256],
        ];
    }


    public function mapDiameters()
    {
        return [
            50 => 50,
            110 => 110,
            160 => 160
        ];
    }

    public function mapTurns()
    {
        return [
            0 => 'нет',
            15 => 15,
            30 => 30,
            45 => 45
        ];
    }

    public function mapFloors()
    {
        return [
            0 => 'Нет',
            1 => "Дерево",
            2 => "Плитка",
            3 => "Кирпич",
            4 => "Газобетон",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'smeta_id' => 'Смета',
            'parent_id' => 'Родитель',
            'id' => 'id',
            'name' => 'Название',
            'diameter' => 'Диаметр',
            'in_ground' => 'В земле',
            'in_air' => 'Подвес',
            'revisions' => 'Ревизия',
            'cascades' => 'Каскад',
            'revisionCascades' => 'Каскад с ревизией',
            'turns' => 'Поворот',
            'rizer' => 'Подъем стояка',
            'floors' => 'Проход пола/стены',
            'zaglushka' => 'Заглушка',
        ];
    }
}
