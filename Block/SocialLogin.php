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

namespace Lof\SocialLogin\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\ObjectManagerInterface;
use Lof\SocialLogin\Helper\Data as HelperData;
use Magento\Customer\Model\Session as CustomerSession;

class SocialLogin extends Template
{
    /**
     * @var Lof\SocialLogin\Helper\Data
     */
    protected $helperData;
    protected $_customerUrl;
    protected $customerSession;

    public function __construct(
        Context $context,
        HelperData $helperData,
        \Magento\Customer\Model\Url $customerUrl,
        ObjectManagerInterface $objectManager,
        CustomerSession $customerSession,
        array $data = []
    ) {
        $this->objectManager   = $objectManager;
        $this->helperData      = $helperData;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
        $this->_customerUrl     = $customerUrl;
    }

    /**
     * get is secure url
     *
     * @return mixed
     */
    public function isSecure()
    {
        return $this->helperData->isSecure();
    }

    /**
     * get Social Login Form Url
     *
     * @return string
     */
    public function getFormLoginUrl()
    {
        return $this->getUrl('lofsociallogin/popup/login', ['_secure' => $this->isSecure()]);
    }

    /**
     *  get Social Login Form Create Url
     *
     * @return string
     */
    public function getCreateFormUrl()
    {
        return $this->getUrl('lofsociallogin/popup/create', ['_secure' => $this->isSecure()]);
    }

    /**
     * get Social Login Forgot Url
     */
    public function getForgotFormUrl()
    {
        return $this->getUrl('lofsociallogin/popup/forgot', ['_secure' => $this->isSecure()]);
    }

    public function getPopupEffect()
    {
        return $this->helperData->getPopupEffect();
    }

    public function isEnabled()
    {
        return $this->helperData->isEnabled() && !$this->_customerSession->isLoggedIn();
    }

    public function getStyleColor()
    {
        return $this->helperData->getStyleManagement();
    }

    public function getCustomCss()
    {
        $storeId   = $this->_storeManager->getStore()->getId(); //add
        return $this->helperData->getCustomCss($storeId);
    }
}
