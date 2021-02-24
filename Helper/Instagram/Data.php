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
namespace Lof\SocialLogin\Helper\Instagram;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

 
    const XML_PATH_INSTAGRAM_CLIENT_ID = 'sociallogin/instagram/client_id';
    const XML_PATH_INSTAGRAM_CLIENT_SECRET = 'sociallogin/instagram/client_secret';
    const XML_PATH_INSTAGRAM_REDIRECT_URL = 'sociallogin/instagram/redirect_url';
    const XML_PATH_INSTAGRAM_SEND_PASSWORD = 'sociallogin/instagram/send_password';
   
    public function getClientId($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_INSTAGRAM_CLIENT_ID, $storeId);
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_INSTAGRAM_SEND_PASSWORD, $storeId);
    }

    public function getClientSecret($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_INSTAGRAM_CLIENT_SECRET, $storeId);
    }

    public function getRedirectUrl($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_INSTAGRAM_REDIRECT_URL, $storeId);
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/instagram/callback', ['_secure' => $this->isSecure()]);
    }

    public function getUrl($path)
    {
        return $this->_getUrl($path, ['_secure' => $this->isSecure()]);
    }
}
