<?php
/**
 * Product's types model for the module yii2-showroom
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "{{%showroom_types}}".
 *
 * Table fields:
 * @property integer $id type identifier
 * @property string $slug unique slug for generate URL
 * @property string $name type name
 *
 * Relations:
 * @property Product[] $products list of products with this type
 */
class Type extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%showroom_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['slug', 'name'], 'string', 'max' => 255],
            ['slug', 'unique']
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
            'name' => Yii::t('showroom_general', 'Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['type_id' => 'id']);
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
            ]
        ];
    }

    /** @inheritdoc */
    public function beforeDelete()
    {
        if(parent::beforeDelete() && $this->getProducts()->count()==0){
            return true;
        }
        return false;
    }
}
