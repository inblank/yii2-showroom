<?php

namespace inblank\showroom;

use yii;
use yii\base\Module as BaseModule;

/**
 * This is the main module class
 *
 * @property array $modelMap
 *
 * @author Pavel Aleksandrov <inblank@yandex.ru>
 */
class Module extends BaseModule
{
    const VERSION = '0.1.0';

    /** @var array Model map */
    public $modelMap = [];
    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'showroom';
    /**
     * Seller logo path relative to @webroot
     * @var string
     */
    public $logoPath = '/images/sellers';
    /**
     * Default logo for seller
     * @var string
     */
    public $defaultLogo = 'logo.svg';
    /**
     * Size of logo image.
     * If array: [width, height].
     * If integer: use value for with and height.
     * If not set: image save as is.
     * @var array|integer
     */
    public $logoSize;

    /** @var array The rules for frontend to be used in URL management. */
    public $urlRulesFrontend = [
    ];

    public $frontendUrlManager;

    /** @var array The rules for backend to be used in URL management. */
    public $urlRulesBackend = [
    ];

    public function getViewPath()
    {
        return defined('IS_BACKEND') ? $this->getBasePath() . DIRECTORY_SEPARATOR . 'views/_backend' : parent::getViewPath();
    }
}
