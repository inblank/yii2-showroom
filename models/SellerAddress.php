<?php

namespace inblank\showroom\models;

use inblank\jsonfields\JSONfieldsBehavior;
use inblank\showroom\traits\CommonTrait;
use inblank\sortable\SortableBehavior;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%showroom_sellers_addresses}}".
 *
 * @property integer $id seller address identifier
 * @property integer $seller_id seller identifier
 * @property integer $sort sort index
 * @property string $title address title
 * @property double $lat address latitude
 * @property double $lng address longitude
 * @property string $address address as string
 * @property string $emails email list as json string
 * @property string $phones phones list as json string
 * @property string $persons persons list as json string
 * @property string $schedule work schedule as json string
 * @property string $description address description
 *
 * @property Seller $seller seller
 */
class SellerAddress extends ActiveRecord
{
    use CommonTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_sellers_addresses}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'address'], 'required'],
            [['seller_id', 'sort'], 'integer'],
            [['lat', 'lng'], 'number'],
            [['address', 'emails', 'phones', 'persons', 'schedule', 'description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => $this->di('Seller'), 'targetAttribute' => ['seller_id' => 'id']],
            [['lat', 'lng'], 'default', 'value'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('showroom_general', 'ID'),
            'seller_id' => Yii::t('showroom_general', 'Seller'),
            'sort' => Yii::t('showroom_general', 'Sort'),
            'title' => Yii::t('showroom_general', 'Title'),
            'lat' => Yii::t('showroom_general', 'Lat'),
            'lng' => Yii::t('showroom_general', 'Lng'),
            'address' => Yii::t('showroom_general', 'Address'),
            'emails' => Yii::t('showroom_general', 'Emails'),
            'phones' => Yii::t('showroom_general', 'Phones'),
            'persons' => Yii::t('showroom_general', 'Persons'),
            'schedule' => Yii::t('showroom_general', 'Schedule'),
            'description' => Yii::t('showroom_general', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne($this->di('Seller'), ['id' => 'seller_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => SortableBehavior::className(),
                'conditionAttributes' => 'seller_id',
            ],
            [
                'class' => JSONfieldsBehavior::className(),
                'attributes' => ['emails', 'phones', 'persons', 'schedule']
            ]
        ];
    }
}
