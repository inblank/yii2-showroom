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
 * @property integer $type type of product. self::GOODS or self::SERVICE
 * @property integer $seller_id product seller identifier.
 *  If defined, mean that the product owned by only this seller
 *  null - means that the product define by site admin and may be exists in any seller.
 * @property integer $group_id product's group identifier
 * @property string $slug product slug for generate URL
 * @property string $name product name
 * @property string $created_at product creation date
 *
 * Relations
 * @property CategoriesProducts[] $categoriesProducts
 * @property Group $group product type
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
            [['seller_id', 'group_id'], 'integer'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['slug', 'name'], 'string', 'max' => 255],
            ['slug', 'unique'],
            ['group_id', 'exist', 'targetClass' => Group::className(), 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'type' => Yii::t('showroom_general', 'Type'),
            'seller_id' => Yii::t('showroom_general', 'Seller'),
            'group_id' => Yii::t('showroom_general', 'Group'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'name' => Yii::t('showroom_general', 'Name'),
            'created_at' => Yii::t('showroom_general', 'Created At'),
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
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
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
