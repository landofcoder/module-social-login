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
namespace Lof\SocialLogin\Helper\Pinterest;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

 
    const XML_PATH_PINTEREST_API_KEY = 'sociallogin/pinterest/api_key';
    const XML_PATH_PINTEREST_CLIENT_KEY = 'sociallogin/pinterest/client_key';
    const XML_PATH_PINTEREST_SEND_PASSWORD = 'sociallogin/pinterest/send_password';
    
    public function getApiKey($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PINTEREST_API_KEY, $storeId);
    }

    public function getClientKey($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PINTEREST_CLIENT_KEY, $storeId);
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PINTEREST_SEND_PASSWORD, $storeId);
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/pinterest/callback', ['_secure' => $this->isSecure()]);
    }
}
