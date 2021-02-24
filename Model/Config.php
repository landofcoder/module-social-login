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

class Config
{
    const FACEBOOK      = 'facebook';
    const TWITTER       = 'twitter';
    const GOOGLE        = 'google';
    const AMAZON        = 'amazon';
    const LINKEDIN      = 'linkedin';
    const GITHUB        = 'github';
    const INSTAGRAM     = 'instagram';
    const WORDPRESS     = 'wordpress';
    const STACKOVERFLOW = 'stackoverflow';
    const PAYPAL        = 'paypal';
    const DISQUS        = 'disqus';
    const PINTEREST     = 'pinterest';
    const VIMEO         = 'vimeo';
    const DROPBOX       = 'dropbox';
    const SOUNDCLOUD    = 'soundcloud';
    const WINDOWSLIVE   = 'windowslive';
    const FOURSQUARE    = 'foursquare';
    const TWITCH        = 'twitch';
    const SLACK        = 'slack';
    const WEIBO        = 'weibo';
    const WECHAT        = 'wechat';

    public static function getSocialNetworks()
    {
        return [
            self::FACEBOOK      => __('Facebook'),
            self::TWITTER       => __('Twitter'),
            self::GOOGLE        => __('Google'),
            self::AMAZON        => __('Amazon'),
            self::LINKEDIN      => __('Linkedin'),
            self::GITHUB        => __('Github'),
            self::INSTAGRAM     => __('Instagram'),
            self::WORDPRESS     => __('Wordpress'),
            self::STACKOVERFLOW => __('Stackoverflow'),
            self::PAYPAL        => __('Paypal'),
            self::DISQUS        => __('Disqus'),
            self::PINTEREST     => __('Pinterest'),
            self::VIMEO         => __('Vimeo'),
            self::DROPBOX       => __('Dropbox'),
            self::SOUNDCLOUD    => __('Soundcloud'),
            self::WINDOWSLIVE    => __('Windows Live'),
            self::FOURSQUARE    => __('Foursquare'),
            self::TWITCH    => __('Twitch'),
            self::SLACK   => __('Slack'),
            self::WEIBO   => __('Weibo'),
            self::WECHAT   => __('Wechat')
        ];
    }
}
