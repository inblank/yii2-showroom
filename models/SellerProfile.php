<?php

namespace inblank\showroom\models;

use Yii;

/**
 * This is the model class for table "{{%showroom_sellers_profiles}}".
 *
 * Table fields:
 * @property integer $seller_id seller identifier
 * @property string $web seller web site
 * @property string $description seller description
 *
 * Relations:
 * @property Seller $seller
 */
class SellerProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_sellers_profiles}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['seller_id', 'required'],
            ['description', 'string'],
            ['description', 'default', 'value'=>''],
            ['web', 'string', 'max' => 255],
            ['seller_id', 'exist', 'skipOnError' => true,
                'targetClass' => Seller::className(),
                'targetAttribute' => ['seller_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => Yii::t('showroom_general', 'Seller ID'),
            'web' => Yii::t('showroom_general', 'Web'),
            'description' => Yii::t('showroom_general', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Seller::className(), ['id' => 'seller_id']);
    }
}
