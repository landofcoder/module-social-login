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

namespace Lof\SocialLogin\Model;

use Lof\SocialLogin\Helper\Facebook\Data as DataHelper;   

class FacebookApp
{

    protected $dataHelper;

    public function __construct(DataHelper $dataHelper)
    {
        $this->dataHelper = $dataHelper;  

    } 
    /**
     * get facebook url api
     *
     * @return type
     */
    public function getFacebookLoginUrl()
    {
        $facebook = $this->newFacebook(); 
        $helper = $facebook->getRedirectLoginHelper();  
        $permissions = ['email']; 
        $url = $this->dataHelper->getAuthUrl();
        $loginUrl = $helper->getLoginUrl($url, $permissions);    
        return $loginUrl;
    }

    /**
     * inital facebook authentication
     *
     * @return \Facebook
     */
    public function newFacebook()
    {
        $fb = new \Facebook\Facebook([
            'app_id'  => $this->dataHelper->getAppId(),
            'app_secret' => $this->dataHelper->getAppSecret(), 
        ]);
        return $fb;
    }
}
