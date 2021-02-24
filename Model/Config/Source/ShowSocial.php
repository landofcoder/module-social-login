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
 * @copyright  Copyright (c) 2016 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\SocialLogin\Model\Config\Source;

class ShowSocial implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'facebook', 'label' => 'Facebook'],
            ['value' => 'soundcloud', 'label' => 'Soundcloud'],
            ['value' => 'github', 'label' => 'Github'],
            ['value' => 'dropbox', 'label' => 'Dropbox'],
            ['value' => 'vimeo', 'label' => 'Vimeo'],
            ['value' => 'pinterest', 'label' => 'Pinterest'],
            ['value' => 'stackoverflow', 'label' => 'Stackoverflow'],
            ['value' => 'disqus', 'label' => 'Disqus'],
            ['value' => 'twitter', 'label' => 'Twitter'],
            ['value' => 'amazon', 'label' => 'Amazon'],
            ['value' => 'linkedin', 'label' => 'Linkedin'],
            ['value' => 'google', 'label' => 'Google'],
            ['value' => 'paypal', 'label' => 'Paypal'],
            ['value' => 'wordpress', 'label' => 'Wordpress'],
            ['value' => 'instagram', 'label' => 'Instagram']
        ];
    }
}
