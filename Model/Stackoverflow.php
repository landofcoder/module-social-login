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

use Lof\SocialLogin\Helper\Stackoverflow\Data as DataHelper;

class Stackoverflow
{

 
    protected $dataHelper;
    public function __construct(DataHelper $dataHelper)
    {
        $this->dataHelper = $dataHelper;
    }
  
    public function getStackoverflowLoginUrl()
    {
        $wpcc_state = md5(mt_rand());
        $_SESSION['wpcc_state_stackoverflow'] = $wpcc_state;
        $url_to = 'https://stackexchange.com/oauth' . '?' . http_build_query([
        'response_type' => 'code',
        'client_id'     => $this->dataHelper->getApiKey(),
        'state'         => $wpcc_state,
        'redirect_uri'  => $this->dataHelper->getAuthUrl(),
        'scope'         => 'private_info'
        ]);
        return $url_to;
    }
}
