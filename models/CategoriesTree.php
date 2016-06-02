<?php
/**
 * Categories tree model for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use Yii;

/**
 * This is the model class for table "{{%showroom_categories_tree}}".
 *
 * Table fields:
 * @property integer $id unique node identifier
 * @property integer $lft node left key
 * @property integer $rgt node right key
 * @property integer $depth node level
 * @property integer $tree node tree identifier
 * @property integer $category_id category identifier
 *
 * Relation:
 * @property Category $category category
 */
class CategoriesTree extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_categories_tree}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lft', 'rgt', 'depth', 'tree', 'category_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'lft' => Yii::t('showroom_general', 'Left'),
            'rgt' => Yii::t('showroom_general', 'Right'),
            'depth' => Yii::t('showroom_general', 'Depth'),
            'tree' => Yii::t('showroom_general', 'Tree'),
            'category_id' => Yii::t('showroom_general', 'Category ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

}
