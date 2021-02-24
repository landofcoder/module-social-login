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

namespace Lof\SocialLogin\Block\Widget;

use Lof\SocialLogin\Block\SocialLogin\Social;

class Login extends Social implements \Magento\Widget\Block\BlockInterface
{
    protected $_authenicationBlock; 

    public function _toHtml()
    {
        if ($this->helperData->isEnabled()) {
            $this->setTemplate('Lof_SocialLogin::widget/login.phtml');
            return parent::_toHtml();
        }
        return;
    }

    public function checkLogin() {
        return $this->_customerSession->isLoggedIn();
    } 

    public function getConfig($key, $default = '')
    {
        if ($this->hasData($key)) {
            return $this->getData($key);
        }
        return $default;
    }
}
