<?php
/**
 * Categories model for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%showroom_categories}}".
 *
 * Table fields:
 * @property integer $id category identifier
 * @property string $slug unique category slug for generate URL
 * @property string $name category name
 * @property string $created_at category created date
 * @property string $deleted_at category deleted date
 *
 * Relations:
 * @property CategoriesProducts[] $categoryProducts
 * @property CategoriesTree[] $categoryTrees
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_categories}}';
    }

    /**
     * @inheritdoc
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['created_at', 'deleted_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['slug', 'name'], 'string', 'max' => 255],
            ['slug', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'name' => Yii::t('showroom_general', 'Name'),
            'created_at' => Yii::t('showroom_general', 'Created At'),
            'deleted_at' => Yii::t('showroom_general', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->via('categoriesProducts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesProducts()
    {
        return $this->hasMany(CategoriesProducts::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getCategoryTrees()
//    {
//        return $this->hasMany(CategoriesTree::className(), ['category_id' => 'id']);
//    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /** @inheritdoc */
    public function beforeDelete()
    {
        if(parent::beforeDelete() && $this->getCategoriesProducts()->count()==0){
            return true;
        }
        return false;
    }

}
