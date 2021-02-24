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

require_once 'Instagram/Instagram.php';
use Lof\SocialLogin\Helper\Instagram\Data as DataHelper;

class Instagram
{

 
    protected $dataHelper;

    public function __construct(DataHelper $dataHelper)
    {

        $this->dataHelper = $dataHelper;
    }
    
    public function getInstagramLoginUrl()
    {
        $instagram = $this->newInstagram();
        $loginUrl = $instagram->getLoginUrl();
        return $loginUrl;
    }

    public function newInstagram()
    {
        $instagram = new Instagram\Instagram([
            'apiKey' => $this->dataHelper->getClientId(),
            'apiSecret' => $this->dataHelper->getClientSecret(),
            'apiCallback' => $this->dataHelper->getAuthUrl()
            ]);
        return $instagram;
    }
}
