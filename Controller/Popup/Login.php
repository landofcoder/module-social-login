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

namespace Lof\SocialLogin\Controller\Popup;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Lof\SocialLogin\Helper\Data as HelperData;

class Login extends Action
{
    protected $helperData;
    protected $accountManagement;
    protected $customerSession;
    protected $jsonHelper;

    public function __construct(
        Context $context,
        HelperData $helperData,
        AccountManagementInterface $accountManagement,
        CustomerSession $customerSession,
        JsonHelper $jsonHelper
    ) {
        parent::__construct($context);
        $this->helperData        = $helperData;
        $this->accountManagement = $accountManagement;
        $this->customerSession   = $customerSession;
        $this->jsonHelper        = $jsonHelper;
    }

    public function execute()
    {
        $username      = $this->getRequest()->getParam('username', false);
        $password      = $this->getRequest()->getParam('password', false);
        $captchaStatus = $this->customerSession->getResultCaptcha();
        if ($captchaStatus) {
            if (isset($captchaStatus['error'])) {
                $this->customerSession->setResultCaptcha(null);
                $this->getResponse()->setBody($this->jsonHelper->jsonEncode($captchaStatus));
            }
        } else {
            $result = [
                'success' => false,
                'message' => []
                ];
            if ($username && $password) {
                try {
                    $accountManage = $this->accountManagement;
                    $customer      = $accountManage->authenticate(
                        $username,
                        $password
                    );
                    $this->customerSession->setCustomerDataAsLoggedIn($customer);
                    $this->customerSession->regenerateId();
                } catch (\Exception $e) {
                    $result['error']   = true;
                    $result['message'] = $e->getMessage();
                }
                if (!isset($result['error'])) {
                    $result['success'] = true;
                    $result['message'] = __('Login successfully. Please wait ...');
                }
            } else {
                $result['error']   = true;
                $result['message'] = __(
                    'Please enter a username and password.'
                );
            }
            $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
        }
    }
}
