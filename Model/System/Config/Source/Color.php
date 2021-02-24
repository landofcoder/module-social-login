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

namespace Lof\SocialLogin\Model\System\Config\Source;

class Color implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '#3399cc', 'label' => __('Default')],
            ['value' => 'orange', 'label' => __('Orange')],
            ['value' => 'green', 'label' => __('Green')],
            ['value' => 'black', 'label' => __('Black')],
            ['value' => 'blue', 'label' => __('Blue')],
            ['value' => 'darkblue', 'label' => __('Dark Blue')],
            ['value' => 'pink', 'label' => __('Pink')],
            ['value' => 'red', 'label' => __('Red')],
            ['value' => 'violet', 'label' => __('Violet')],
            ['value' => 'custom', 'label' => __('Custom')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            '#3399cc'  => __('Default'),
            'orange'   => __('Orange'),
            'green'    => __('Green'),
            'black'    => __('Black'),
            'blue'     => __('Blue'),
            'darkblue' => __('Dark Blue'),
            'pink'     => __('Pink'),
            'red'      => __('Red'),
            'violet'   => __('Violet'),
            'custom'   => __('Custom'),
        ];
    }
}
