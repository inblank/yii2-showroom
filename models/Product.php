<?php
/**
 * Products model for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use inblank\showroom\traits\CommonTrait;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%showroom_products}}".
 *
 * Table fields:
 * @property integer $id product identifier
 * @property integer $type type of product. self::TYPE_GOODS or self::TYPE_SERVICE
 * @property integer $seller_id product seller identifier.
 *  If defined, mean that the product owned by only this seller
 *  null - means that the product define by site admin and may be exists in any seller.
 * @property integer $group_id product's group identifier
 * @property string $slug product slug for generate URL
 * @property string $name product name
 * @property string $shortname product shortname
 * @property string $created_at product creation date
 *
 * Relations
 * @property CategoriesProducts[] $categoriesProducts
 * @property Group $group product type
 */
class Product extends \yii\db\ActiveRecord
{
    use CommonTrait;

    /** Product is goods */
    const TYPE_GOODS = 1;
    /** Product is service */
    const TYPE_SERVICE = 2;

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
            [['slug', 'name', 'shortname'], 'string', 'max' => 255],
            ['slug', 'unique'],
            ['group_id', 'exist', 'targetClass' => Group::className(), 'targetAttribute' => 'id'],
            ['type', 'in', 'range' => [self::TYPE_GOODS, self::TYPE_SERVICE]],
            ['shortname', 'default', 'value' => function(){
                return $this->name;
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            // table's attributes
            'id' => Yii::t('showroom_general', 'ID'),
            'type' => Yii::t('showroom_general', 'Type'),
            'seller_id' => Yii::t('showroom_general', 'Seller'),
            'group_id' => Yii::t('showroom_general', 'Group'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'name' => Yii::t('showroom_general', 'Name'),
            'shortname' => Yii::t('showroom_general', 'Short Name'),
            'created_at' => Yii::t('showroom_general', 'Created'),

            // other attributes
            'seller' => Yii::t('showroom_general', 'Seller'),
            'group' => Yii::t('showroom_general', 'Group'),
            'typeText' => Yii::t('showroom_general', 'Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(self::di('Category'), ['id' => 'category_id'])->via('categoriesProducts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(self::di('Seller'), ['id' => 'category_id'])->via('categoriesProducts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriesProducts()
    {
        return $this->hasMany(self::di('CategoriesProducts'), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(self::di('Group'), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(self::di('Price'), ['product_id' => 'id']);
    }

    /**
     * Get product readable type
     */
    public function getTypeText(){
        switch($this->type){
            case self::TYPE_GOODS:
                return Yii::t('showroom_general', 'Goods');
            case self::TYPE_SERVICE:
                return Yii::t('showroom_general', 'Service');
            default:
                return null;
        }
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
