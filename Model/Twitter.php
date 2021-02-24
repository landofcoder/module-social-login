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

use Lof\SocialLogin\Helper\Twitter\Data as DataHelper;  
use \Abraham\TwitterOAuth\TwitterOAuth;

class Twitter
{

    protected $dataHelper;

    public function __construct(DataHelper $dataHelper)
    {

        $this->dataHelper = $dataHelper;
    }
    public function getTwitterLoginUrl()
    {

        $settings = [
        'consumer_key' => $this->dataHelper->getConsumerKey(),
        'consumer_secret' => $this->dataHelper->getConsumerSecret(),
        ];
        return $settings;
    }

    public function newLoginTwitter()
    {
        $settings = [
        'consumer_key' => $this->dataHelper->getConsumerKey(),
        'consumer_secret' => $this->dataHelper->getConsumerSecret(),
        ];
        $connection = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret']);
        return $connection;
    }

    public function callbackTwitter()
    {
        $settings = [
        'consumer_key' => $this->dataHelper->getConsumerKey(),
        'consumer_secret' => $this->dataHelper->getConsumerSecret(),
        ];
        $connection = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        return $connection;
    }
    public function getDataCallbackTwitter()
    {
        $access_token = $_SESSION['access_token'];
        $settings = [
        'consumer_key' => $this->dataHelper->getConsumerKey(),
        'consumer_secret' => $this->dataHelper->getConsumerSecret(),
        ];
        $connection = new TwitterOAuth($settings['consumer_key'], $settings['consumer_secret'], $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user = $connection->get("account/verify_credentials", ['include_email'=>'true']);
        return $user;
    }
}
