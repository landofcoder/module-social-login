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

class PopupLayout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
         $data = [];
         $data[] = [
            'value' => 'lof-social-login-style1',
            'label' => __('Style 1')
                ];
        $data[] = [
            'value' => 'lof-social-login-style2',
            'label' => __('Style 2')
                ];   
         $data[] = [
            'value' => 'lof-social-login-style3',
            'label' => __('Style 3')
                ];
        $data[] = [
            'value' => 'lof-social-login-style4',
            'label' => __('Style 4')
                ];           
        return $data;
    }
}
