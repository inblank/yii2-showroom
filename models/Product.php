<?php
/**
 * Products model for the module yii2-showroom
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
 * This is the model class for table "{{%showroom_products}}".
 *
 * Table fields:
 * @property integer $id product identifier
 * @property integer $seller_id product seller identifier.
 *  If defined, mean that the product owned by only this seller
 *  null - means that the product define by site admin and may be exists in any seller.
 * @property integer $type_id product's type identifier
 * @property string $slug product slug for generate URL
 * @property string $name product name
 * @property string $created_at product creation date
 * @property string $deleted_at product delete date
 *
 * Relations
 * @property CategoriesProducts[] $categoriesProducts
 * @property Type $type product type
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_products}}';
    }

    /**
     * @inheritdoc
     * @return ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['seller_id', 'type_id'], 'integer'],
            [['created_at', 'deleted_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['slug', 'name'], 'string', 'max' => 255],
            ['slug', 'unique'],
            ['type_id', 'exist', 'targetClass' => Type::className(), 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'seller_id' => Yii::t('showroom_general', 'Seller ID'),
            'type_id' => Yii::t('showroom_general', 'Type ID'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'name' => Yii::t('showroom_general', 'Name'),
            'created_at' => Yii::t('showroom_general', 'Created At'),
            'deleted_at' => Yii::t('showroom_general', 'Deleted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->via('categoriesProducts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesProducts()
    {
        return $this->hasMany(CategoriesProducts::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(Type::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(Price::className(), ['product_id' => 'id']);
    }

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
        if (parent::beforeDelete() && ($this->seller_id !== null || $this->getPrices()->count() == 0)) {
            return true;
        }
        return false;
    }
}
