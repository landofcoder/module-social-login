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
    protected $storemanagerInterface;

    public function __construct(
        DataHelper $dataHelper,
        \Magento\Store\Model\StoreManagerInterface $storemanagerInterface
    )
    {
        $this->storemanagerInterface = $storemanagerInterface;
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
        'scope'         => 'profile'
        ]);
        $url_to .= '&redirect_uri='.$this->getBaseUrl();
        return $url_to;
    }
    public function getBaseUrl()
    {
        $baseUrl = $this->storemanagerInterface
        ->getStore()
        ->getBaseUrl();

        return $baseUrl.'lofsociallogin/amazon/callback';
    }
}
