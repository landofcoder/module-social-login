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

use Lof\SocialLogin\Helper\Amazon\Data as DataHelper;

class Amazon
{

    protected $dataHelper;

    public function __construct(DataHelper $dataHelper)
    {

        $this->dataHelper = $dataHelper;
    }
  
    public function getAmazonLoginUrl()
    {
        $wpcc_state = md5(mt_rand());
        $_SESSION['wpcc_state_amazon'] = $wpcc_state;
        $url_to = 'https://www.amazon.com/ap/oa'.'?' . http_build_query([
        'response_type' => 'code',
        'client_id'     => $this->dataHelper->getApiKey(),
        'state'         => $wpcc_state,
        'redirect_uri'  => $this->getBaseUrl(),
        'scope'         => 'profile'
        ]);
        return $url_to;
    }
    public function getBaseUrl()
    {
        $objectmanager     = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectmanager->get('Magento\Store\Model\StoreManagerInterface')
        ->getStore()
        ->getBaseUrl();

        return $helper.'lofsociallogin/amazon/callback';
    }
}
