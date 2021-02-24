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
namespace Lof\SocialLogin\Helper\Soundcloud;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

 
    const XML_PATH_SOUNDCLOUD_API_KEY = 'sociallogin/soundcloud/api_key';
    const XML_PATH_SOUNDCLOUD_CLIENT_KEY = 'sociallogin/soundcloud/client_key';
    const XML_PATH_SOUNDCLOUD_SEND_PASSWORD = 'sociallogin/soundcloud/send_password';
    
    
    public function getApiKey($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SOUNDCLOUD_API_KEY, $storeId);
    }

    public function getClientKey($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SOUNDCLOUD_CLIENT_KEY, $storeId);
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SOUNDCLOUD_SEND_PASSWORD, $storeId);
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/soundcloud/callback', ['_secure' => $this->isSecure()]);
    }
}
