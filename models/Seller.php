<?php

namespace inblank\showroom\models;

use inblank\showroom\traits\CommonTrait;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%showroom_sellers}}".
 *
 * Table fields:
 * @property integer $id seller identifier
 * @property integer $user_id user identifier
 * @property string $name seller name
 * @property string $slug seller unique slug to generator URL
 * @property string $created_at date of seller creation
 *
 * Relations:
 * @property Product[] $Products list of seller products
 * @property SellerProfile $sellerProfile seller profile
 */
class Seller extends \yii\db\ActiveRecord
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
            [['name', 'user_id'], 'required'],
            [['user_id'], 'integer'],
            ['user_id', 'exist',
                'targetClass' => Yii::createObject('inblank\showroom\models\User')->className(),
                'targetAttribute' => 'id',
                'skipOnError' => true
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
            'user_id' => Yii::t('showroom_general', 'User ID'),
            'name' => Yii::t('showroom_general', 'Name'),
            'slug' => Yii::t('showroom_general', 'Slug'),
            'created_at' => Yii::t('showroom_general', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['seller_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        $className = Yii::createObject('inblank\showroom\models\User')->className();
        return $this->hasOne($className, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShowroomSellersProfiles()
    {
        return $this->hasOne(SellerProfile::className(), ['seller_id' => 'id']);
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
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            // create profile
            (new SellerProfile(['seller_id' => $this->id]))->save();
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // delete profile
        SellerProfile::deleteAll(['seller_id' => $this->id]);
    }
}
