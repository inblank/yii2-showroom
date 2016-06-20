<?php
/**
 * Seller model for the module yii2-activeuser
 *
 * @link https://github.com/inblank/yii2-activeuser
 * @copyright Copyright (c) 2016 Pavel Aleksandrov <inblank@yandex.ru>
 * @license http://opensource.org/licenses/MIT
 */
namespace inblank\showroom\models;

use Imagine\Image\Box;
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
 * @property Product[] $Products list of seller products
 * @property SellerProfile $profile seller profile
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
            [['name', 'slug', 'logo'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            ['logo', 'default', 'value' => $this->getModule()->defaultLogo],
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

    /**
     * Get logo images absolute path in filesystem.
     * If path not exist, try to create him
     * @return string
     * @throws yii\base\Exception
     * @throws yii\base\InvalidConfigException
     */
    public static function getLogoPath()
    {
        static $path;
        if ($path === null) {
            $path = Yii::getAlias('@webroot') . '/' . (defined('IS_BACKEND') ? '../' : '') . trim(self::getModule()->logoPath, '/');
            if (!file_exists($path)) {
                yii\helpers\FileHelper::createDirectory($path);
            }
        }
        return $path;
    }

    /**
     * Remove seller's current logo image
     * @throws yii\base\InvalidConfigException
     */
    protected function removeCurrentLogoImage()
    {
        if (!empty($this->logo) && $this->logo != $this->getModule()->defaultLogo) {
            // remove old logo
            @unlink(self::getLogoPath() . '/' . $this->logo);
        }
    }

    /**
     * Change seller logo
     * @param $sourceFile
     * @return bool if logo changed true
     */
    public function changeLogo($sourceFile)
    {
        if (!file_exists($sourceFile)) {
            return false;
        }
        $logoPath = self::getLogoPath();
        $fileName = md5($this->id . microtime(true) . rand()) . '.' . pathinfo($sourceFile)['extension'];
        $destinationFile = $logoPath . '/' . $fileName;
        if (!copy($sourceFile, $destinationFile)) {
            return false;
        }
        $this->removeCurrentLogoImage();
        $size = $this->getModule()->logoSize;
        if (!empty($size)) {
            if (!is_array($size)) {
                $size = [$size, $size];
            }
            yii\imagine\Image::getImagine()
                ->open($destinationFile)
                ->resize(new Box($size[0], $size[1]))
                ->save($destinationFile);
        }
        $this->logo = $fileName;
        $this->updateAttributes(['logo']);
        return true;
    }

    /**
     * Reset seller's logo to default
     * @throws yii\base\InvalidConfigException
     */
    public function resetLogo()
    {
        $this->removeCurrentLogoImage();
        $this->logo = $this->getModule()->defaultLogo;
        $this->updateAttributes(['logo']);
    }
}
