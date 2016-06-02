<?php
/**
 * Model for relation between products and category for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use inblank\sortable\SortableBehavior;
use Yii;

/**
 * This is the model class for table "{{%showroom_categories_products}}".
 *
 * Table fields:
 * @property integer $category_id category identifier
 * @property integer $product_id product identifier
 * @property integer $sort sort index
 *
 * Relations:
 * @property Category $category category
 * @property Product $product product
 */
class CategoriesProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_categories_products}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id'], 'required'],
            [['category_id', 'product_id', 'sort'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => 'id'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => Yii::t('showroom_general', 'Category ID'),
            'product_id' => Yii::t('showroom_general', 'Product ID'),
            'sort' => Yii::t('showroom_general', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'sortable' => [
                'class' => SortableBehavior::className(),
                'sortAttribute' => 'sort',
                'conditionAttributes' => 'category_id'
            ]
        ];
    }
}
