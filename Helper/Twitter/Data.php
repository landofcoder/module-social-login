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
namespace Lof\SocialLogin\Helper\Twitter;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

 
    const XML_PATH_TWITTER_CONSUMER_KEY = 'sociallogin/twitter/consumer_key';
    const XML_PATH_TWITTER_CONSUMER_SECRET = 'sociallogin/twitter/consumer_secret';
    const XML_PATH_TWITTER_ACCESS_TOKEN = 'sociallogin/twitter/oauth_access_token';
    const XML_PATH_TWITTER_ACCESS_TOKEN_SECRET = 'sociallogin/twitter/oauth_access_token_secret';
    const XML_PATH_TWITTER_SEND_PASSWORD = 'sociallogin/twitter/send_password';

    public function getConsumerKey($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_CONSUMER_KEY, $storeId);
    }

    public function getConsumerSecret($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_CONSUMER_SECRET, $storeId);
    }

    public function getAccesstoken($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_ACCESS_TOKEN, $storeId);
    }

    public function getAcessTokenSecret($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_ACCESS_TOKEN_SECRET, $storeId);
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/twitter/callback', ['_secure' => $this->isSecure()]);
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_SEND_PASSWORD, $storeId);
    }
}
