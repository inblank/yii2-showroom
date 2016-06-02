<?php
/**
 * Product's prices model for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use Yii;

/**
 * This is the model class for table "{{%showroom_prices}}".
 *
 * Table fields:
 * @property integer $seller_id seller identifier
 * @property integer $vendor_id vendor identifier
 * @property integer $product_id product identifier
 * @property double $price seller's price
 * @property integer $available_count product available count
 *
 * Relations:
 * @property \yii\web\User $seller product seller
 * @property Vendor $vendor product vendor
 * @property Product $product product
 */
class Price extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_prices}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'vendor_id', 'product_id'], 'required'],
            [['seller_id', 'vendor_id', 'product_id', 'available_count'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => Yii::t('showroom_general', 'Seller ID'),
            'vendor_id' => Yii::t('showroom_general', 'Vendor ID'),
            'product_id' => Yii::t('showroom_general', 'Product ID'),
            'price' => Yii::t('showroom_general', 'Price'),
            'available_count' => Yii::t('showroom_general', 'Available Count'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller(){
        /** @var \yii\web\User $user */
        $user = Yii::createObject('inblank\showroom\User');
        return $this->hasOne($user->className(), ['id'=>'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVendor(){
        return $this->hasOne(Vendor::className(), ['id'=>'vendor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(){
        return $this->hasOne(Product::className(), ['id'=>'product_id']);
    }
}
