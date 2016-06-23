<?php
/**
 * Seller model for the module yii2-activeuser
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use inblank\image\ImageBehavior;
use inblank\showroom\traits\CommonTrait;
use yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%showroom_sellers}}".
 *
 * Table fields:
 * @property integer $id seller identifier
 * @property integer $user_id user identifier
 * @property string $logo seller logo
 * @property string $name seller name
 * @property string $slug seller unique slug to generator URL
 * @property string $created_at date of seller creation
 *
 * Relations:
 * @property ActiveRecord $user user linked to seller
 * @property Product[] $products list of seller products
 * @property SellerAddress[] $addresses list of seller addresses
 * @property SellerProfile $profile seller profile
 *
 * Methods:
 * @method bool imageChange($filename)
 */
class Seller extends ActiveRecord
{
    use CommonTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_sellers}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['user_id'], 'integer'],
            ['user_id', 'exist',
                'targetClass' => $this->di('User'),
                'targetAttribute' => 'id',
                'skipOnError' => true,
                'skipOnEmpty' => true
            ],
            [['created_at'], 'date',
                'format' => 'php:Y-m-d H:i:s'
            ],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'user_id' => Yii::t('showroom_general', 'User'),
            'logo' => Yii::t('showroom_general', 'Logo'),
            'name' => Yii::t('showroom_general', 'Name'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'created_at' => Yii::t('showroom_general', 'Created'),
        ];
    }

    /**
     * Get list of sellers addresses
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany($this->di('SellerAddress'), ['seller_id' => 'id']);
    }

    /**
     * Get list of sellers products
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany($this->di('Product'), ['seller_id' => 'id']);
    }

    /**
     * Get user linked to seller
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne($this->di('User'), ['id' => 'user_id']);
    }

    /**
     * Get seller profile
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->di('SellerProfile'), ['seller_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
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
            [
                'class' => ImageBehavior::className(),
                'imageAttribute' => 'logo',
                'imageDefault' => 'logo.svg',
                'imageSize' => self::getModule()->logoSize,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // create profile
            Yii::createObject([
                'class' => $this->di('SellerProfile'),
                'seller_id' => $this->id
            ])->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        parent::afterDelete();
        // delete profile
        Yii::createObject($this->di('SellerProfile'))->deleteAll(['seller_id' => $this->id]);
    }

}
