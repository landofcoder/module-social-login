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

use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Helper\Address;
use Magento\Framework\UrlFactory;
use Magento\Customer\Model\Metadata\FormFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Registration;
use Magento\Framework\Escaper;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\InputException;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Create extends AbstractAccount
{
    /** @var AccountManagementInterface */
    protected $accountManagement;

    /** @var Address */
    protected $addressHelper;

    /** @var FormFactory */
    protected $formFactory;

    /** @var SubscriberFactory */
    protected $subscriberFactory;

    /** @var RegionInterfaceFactory */
    protected $regionDataFactory;

    /** @var AddressInterfaceFactory */
    protected $addressDataFactory;

    /** @var Registration */
    protected $registration;

    /** @var CustomerInterfaceFactory */
    protected $customerDataFactory;

    /** @var CustomerUrl */
    protected $customerUrl;

    /** @var Escaper */
    protected $escaper;

    /** @var CustomerExtractor */
    protected $customerExtractor;

    /** @var \Magento\Framework\UrlInterface */
    protected $urlModel;

    /** @var DataObjectHelper */
    protected $dataObjectHelper;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var AccountRedirect
     */
    private $accountRedirect;
    protected $jsonHelper;

    /**
     * @param Context                    $context
     * @param Session                    $customerSession
     * @param ScopeConfigInterface       $scopeConfig
     * @param StoreManagerInterface      $storeManager
     * @param AccountManagementInterface $accountManagement
     * @param Address                    $addressHelper
     * @param UrlFactory                 $urlFactory
     * @param FormFactory                $formFactory
     * @param SubscriberFactory          $subscriberFactory
     * @param RegionInterfaceFactory     $regionDataFactory
     * @param AddressInterfaceFactory    $addressDataFactory
     * @param CustomerInterfaceFactory   $customerDataFactory
     * @param CustomerUrl                $customerUrl
     * @param Registration               $registration
     * @param Escaper                    $escaper
     * @param CustomerExtractor          $customerExtractor
     * @param DataObjectHelper           $dataObjectHelper
     * @param AccountRedirect            $accountRedirect
     *
     * @param JsonHelper                 $jsonHelper
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        AccountManagementInterface $accountManagement,
        Address $addressHelper,
        UrlFactory $urlFactory,
        FormFactory $formFactory,
        SubscriberFactory $subscriberFactory,
        RegionInterfaceFactory $regionDataFactory,
        AddressInterfaceFactory $addressDataFactory,
        CustomerInterfaceFactory $customerDataFactory,
        CustomerUrl $customerUrl,
        Registration $registration,
        Escaper $escaper,
        CustomerExtractor $customerExtractor,
        DataObjectHelper $dataObjectHelper,
        AccountRedirect $accountRedirect,
        JsonHelper $jsonHelper
    ) {
        $this->session             = $customerSession;
        $this->scopeConfig         = $scopeConfig;
        $this->storeManager        = $storeManager;
        $this->accountManagement   = $accountManagement;
        $this->addressHelper       = $addressHelper;
        $this->formFactory         = $formFactory;
        $this->subscriberFactory   = $subscriberFactory;
        $this->regionDataFactory   = $regionDataFactory;
        $this->addressDataFactory  = $addressDataFactory;
        $this->customerDataFactory = $customerDataFactory;
        $this->customerUrl         = $customerUrl;
        $this->registration        = $registration;
        $this->escaper             = $escaper;
        $this->customerExtractor   = $customerExtractor;
        $this->urlModel            = $urlFactory->create();
        $this->dataObjectHelper    = $dataObjectHelper;
        $this->accountRedirect     = $accountRedirect;
        $this->jsonHelper          = $jsonHelper;
        parent::__construct($context);
    }

    /**
     * Add address to customer during create account
     *
     * @return AddressInterface|null
     */
    protected function extractAddress()
    {
        if (!$this->getRequest()->getPost('create_address')) {
            return null;
        }

        $addressForm       = $this->formFactory->create('customer_address', 'customer_register_address');
        $allowedAttributes = $addressForm->getAllowedAttributes();

        $addressData = [];

        $regionDataObject = $this->regionDataFactory->create();
        foreach ($allowedAttributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $value         = $this->getRequest()->getParam($attributeCode);
            if ($value === null) {
                continue;
            }
            switch ($attributeCode) {
                case 'region_id':
                    $regionDataObject->setRegionId($value);
                    break;
                case 'region':
                    $regionDataObject->setRegion($value);
                    break;
                default:
                    $addressData[$attributeCode] = $value;
            }
        }
        $addressDataObject = $this->addressDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $addressDataObject,
            $addressData,
            '\Magento\Customer\Api\Data\AddressInterface'
        );
        $addressDataObject->setRegion($regionDataObject);

        $addressDataObject->setIsDefaultBilling(
            $this->getRequest()->getParam('default_billing', false)
        )->setIsDefaultShipping(
            $this->getRequest()->getParam('default_shipping', false)
        );

            return $addressDataObject;
    }

    /**
     * Create customer account action
     *
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute()
    {
        $captchaStatus = $this->session->getResultCaptcha();
        if ($captchaStatus) {
            if (isset($captchaStatus['error'])) {
                $this->session->setResultCaptcha(null);
                $this->getResponse()->setBody($this->jsonHelper->jsonEncode($captchaStatus));
            }
        } else {
            $result = [
                'success' => false,
                'message' => []
                ];
            $this->session->regenerateId();
            try {
                $address   = $this->extractAddress();
                $addresses = $address === null ? [] : [$address];

                $customer = $this->customerExtractor->extract('customer_account_create', $this->_request);
                $customer->setAddresses($addresses);

                $password     = $this->getRequest()->getParam('password');
                $confirmation = $this->getRequest()->getParam('password_confirmation');
                if (!$this->checkPasswordConfirmation($password, $confirmation)) {
                    $result['error']     = true;
                    $result['message'][] = __('Please make sure your passwords match.');
                }
                if (!isset($result['error']) || !$result['error']) {
                    $customer = $this->accountManagement
                    ->createAccount($customer, $password);

                    if ($this->getRequest()->getParam('is_subscribed', false)) {
                        $this->subscriberFactory->create()->subscribeCustomerById($customer->getId());
                    }

                    $telephone = $this->getRequest()->getParam('telephone');
                    $this->_eventManager->dispatch(
                        'customer_register_success',
                        ['account_controller' => $this, 'customer' => $customer, 'telephone'=> $telephone]
                    );

                    $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
                    if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                        $email = $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());
                        // @codingStandardsIgnoreStart
                        $result['success']   = false;
                        $result['message'][] = __(
                            'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                            $email
                            );
                    } else {
                        $result['success']   = true;
                        $result['message'][] = __(
                            'Create an account successfully. Please wait...'
                            );
                        $this->session->setCustomerDataAsLoggedIn($customer);
                    }
                }
            } catch (StateException $e) {
                $url             = $this->urlModel->getUrl('customer/account/forgotpassword');
                $result['error'] = true;
                // @codingStandardsIgnoreStart
                $result['message'][] = __(
                    'There is already an account with this email address. If you are sure that it is your email address, <a href="%1">click here</a> to get your password and access your account.',
                    $url
                    );
            } catch (InputException $e) {
                $result['error']     = true;
                $result['message'][] = $this->escaper->escapeHtml($e->getMessage());
            } catch (\Exception $e) {
                $result['error']     = true;
                $result['message'][] = $this->escaper->escapeHtml($e->getMessage());
            }

            $this->session->setCustomerFormData($this->getRequest()->getPostValue());
            $this->getResponse()->setBody($this->jsonHelper->jsonEncode($result));
        }
    }

    /**
     * Make sure that password and password confirmation matched
     *
     * @param string $password
     * @param string $confirmation
     * @return void
     * @throws InputException
     */
    protected function checkPasswordConfirmation($password, $confirmation)
    {
        return $password == $confirmation;
    }

}
