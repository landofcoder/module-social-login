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

use Lof\SocialLogin\Helper\Twitch\Data as DataHelper;

class Twitch
{

    protected $dataHelper;

    public function __construct(DataHelper $dataHelper)
    {

        $this->dataHelper = $dataHelper;
    }

    public function getTwitchLoginUrl()
    {
        $wpcc_state = md5(mt_rand());
        $_SESSION['wpcc_state_twitch'] = $wpcc_state;
        $url_to = 'https://api.twitch.tv/kraken/oauth2/authorize' . '?' . http_build_query([
                'client_id'     => $this->dataHelper->getAppId(),
                'scope'         => 'user_read',
                'response_type' => 'code',
                'redirect_uri'  => $this->dataHelper->getAuthUrl(),
                'state'  => $wpcc_state
            ]);
        return $url_to;
    }
}
