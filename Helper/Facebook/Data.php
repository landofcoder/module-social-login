<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_SocialLogin
 *
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */
namespace Lof\SocialLogin\Helper\Facebook;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

    const XML_PATH_FACEBOOK_APP_ID = 'sociallogin/facebook/app_id';
    const XML_PATH_FACEBOOK_APP_SECRET = 'sociallogin/facebook/app_secret';
    const XML_PATH_FACEBOOK_REDIRECT_URL = 'sociallogin/facebook/redirect_url';
    const XML_PATH_FACEBOOK_BUTTON_IMAGE = 'sociallogin/facebook/button_image';
    const XML_PATH_FACEBOOK_BUTTON_IMAGE_LABEL = 'sociallogin/facebook/button_image_label';
    const XML_PATH_FACEBOOK_POSITION_NUMBER = 'sociallogin/facebook/position_number';
    const XML_PATH_FACEBOOK_SEND_PASSWORD = 'sociallogin/facebook/send_password';
    const XML_PATH_SECURE_IN_FRONTEND = 'web/secure/use_in_frontend';
    
    public function getAppId($storeId = null)
    {
        if ($this->getConfigValue(self::XML_PATH_FACEBOOK_APP_ID, $storeId)) {
            return $this->getConfigValue(self::XML_PATH_FACEBOOK_APP_ID, $storeId);
        }
        return '#';
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_SEND_PASSWORD, $storeId);
    }

    public function getAppSecret($storeId = null)
    {
        if ($this->getConfigValue(self::XML_PATH_FACEBOOK_APP_SECRET, $storeId)) {
            return $this->getConfigValue(self::XML_PATH_FACEBOOK_APP_SECRET, $storeId);
        }
        return '#';
    }

    public function getRedirectUrl($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_REDIRECT_URL, $storeId);
    }

    public function getButtonImage($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_BUTTON_IMAGE, $storeId);
    }

    public function getButtonImageLabel($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_BUTTON_IMAGE_LABEL, $storeId);
    }

    public function getPositionNumber($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_POSITION_NUMBER, $storeId);
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/facebook/callback', ['_secure' => $this->isSecure()]);
    }
}
