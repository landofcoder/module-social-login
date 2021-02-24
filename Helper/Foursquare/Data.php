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
namespace Lof\SocialLogin\Helper\Foursquare;

use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;

class Data extends HelperData
{

    const XML_PATH_FOURSQUARE_CLIENT_ID = 'sociallogin/foursquare/client_id';
    const XML_PATH_FOURSQUARE_CLIENT_SECRET = 'sociallogin/foursquare/client_secret';
    const XML_PATH_FOURSQUARE_SEND_PASSWORD = 'sociallogin/foursquare/send_password';
    const XML_PATH_SECURE_IN_FRONTEND = 'web/secure/use_in_frontend';

    public function getAppId($storeId = null)
    {
        if ($this->getConfigValue(self::XML_PATH_FOURSQUARE_CLIENT_ID, $storeId)) {
            return $this->getConfigValue(self::XML_PATH_FOURSQUARE_CLIENT_ID, $storeId);
        }
        return '#';
    }

    public function sendPassword($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FOURSQUARE_SEND_PASSWORD, $storeId);
    }

    public function getAppSecret($storeId = null)
    {
        if ($this->getConfigValue(self::XML_PATH_FOURSQUARE_CLIENT_SECRET, $storeId)) {
            return $this->getConfigValue(self::XML_PATH_FOURSQUARE_CLIENT_SECRET, $storeId);
        }
        return '#';
    }

    public function getAuthUrl()
    {
        return $this->_getUrl('lofsociallogin/foursquare/callback/', ['_secure' => $this->isSecure()]);
    }
}
