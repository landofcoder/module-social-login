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

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Forgot extends Action
{
    /** @var AccountManagementInterface */
    protected $customerAccountManagement;

    /** @var Escaper */
    protected $escaper;

    /**
     * @var Session
     */
    protected $session;
    protected $jsonHelper;

    /**
     * @param Context                    $context
     * @param Session                    $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper                    $escaper
     * @param JsonHelper                 $jsonHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        JsonHelper $jsonHelper
    ) {
        $this->session                   = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper                   = $escaper;
        $this->jsonHelper                = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $result        = [
            'success' => false,
            'message' => []
            ];
        $captchaStatus = $this->session->getResultCaptcha();
        if ($captchaStatus) {
            if (isset($captchaStatus['error'])) {
                $this->session->setResultCaptcha(null);
                $this->getResponse()->setBody($this->jsonHelper->jsonEncode($captchaStatus));
                return;
            }
            $result['imgSrc'] = $captchaStatus['imgSrc'];
        }


        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $email = (string)$this->getRequest()->getPost('email');
        if ($email) {
            if (!\Zend_Validate::is($email, 'EmailAddress')) {
                $this->session->setForgottenEmail($email);
                $result['error']     = true;
                $result['message'][] = __('Please correct the email address.');
            }

            try {
                $this->customerAccountManagement->initiatePasswordReset(
                    $email,
                    AccountManagement::EMAIL_RESET
                );
                $result['success']   = true;
                $result['message'][] = __(
                    'If there is an account associated with %1 you will receive an email with a link to reset your password.',
                    $this->escaper->escapeHtml($email)
                );
            } catch (NoSuchEntityException $e) {
                // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
            } catch (\Exception $exception) {
                $result['error']     = true;
                $result['message'][] = __('We\'re unable to send the password reset email.');
            }
        }
        $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
    }
}
