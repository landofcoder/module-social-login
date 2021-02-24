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

namespace Lof\SocialLogin\Controller\Google;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\CustomerFactory;
use Symfony\Component\Config\Definition\Exception\Exception;
use Lof\SocialLogin\Model\ResourceModel\Social\CollectionFactory as GoogleCollectionFactory;
use Lof\SocialLogin\Model\Google;
use Lof\SocialLogin\Helper\Google\Data as SocialHelper;
use Lof\SocialLogin\Model\SocialFactory as GoogleModelFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Visitor;
use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;

class Callback extends Action
{
    const SOCIAL_TYPE = 'google';
    protected $resultPageFactory;
    protected $google;
    protected $socialHelper;
    protected $accountManagement;
    protected $customerUrl;
    protected $session;
    protected $googleCustomerCollectionFactory;
    protected $googleCustomerModelFactory;
    protected $customerFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private $cookieMetadataManager;

    public function __construct(
        Context $context,
        Google $google,
        StoreManagerInterface $storeManager,
        SocialHelper $socialHelper,
        PageFactory $resultPageFactory,
        AccountManagementInterface $accountManagement,
        CustomerUrl $customerUrl,
        GoogleModelFactory $googleCustomerModelFactory,
        GoogleCollectionFactory $googleCustomerCollectionFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        Visitor $visitor
        ) {
        parent::__construct($context);
        $this->google                          = $google;
        $this->storeManager                    = $storeManager;
        $this->socialHelper                    = $socialHelper;
        $this->resultPageFactory               = $resultPageFactory;
        $this->accountManagement               = $accountManagement;
        $this->customerUrl                     = $customerUrl;
        $this->session                         = $customerSession;
        $this->googleCustomerModelFactory      = $googleCustomerModelFactory;
        $this->googleCustomerCollectionFactory = $googleCustomerCollectionFactory;
        $this->customerFactory                 = $customerFactory;
        $this->customerRepository              = $customerRepository;
        $this->visitor                         = $visitor;
    }

    public function execute()
    {
        $config = [
            'callback' => $this->google->getBaseUrl(),
            'providers' => [ 
                'Google' => [ 
                    'enabled' => true,
                    'keys'    => [ 'id' => $this->getGoogleHelper()->getClientId(), 'secret' => $this->getGoogleHelper()->getClientSecret() ],
                ]
            ]
        ];
        try {    
            $hybridauth = new Hybridauth( $config ); 
            $adapter = $hybridauth->authenticate( 'Google' ); 
            $tokens = $adapter->getAccessToken();
            $dataUser = (array)$adapter->getUserProfile(); 
            $adapter->disconnect();
        }
        catch (\Exception $e) {
            echo $e->getMessage();
        }    
        $redirect = $this->socialHelper->getConfig(('general/redirect_page'));
        if(empty($redirect)){
            $link_redirect = "window.opener.location.reload();";
        }else{
            $link_redirect = "window.opener.location= '".$redirect."';";
        };  
        $data = [];
        $customerId = $this->getCustomerIdByGoogleId($dataUser['identifier']);
        if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);
            $customer1 = $this->customerFactory->create()->load($customerId);
            if ($customer->getConfirmation()) {
                try {
                    $customer1->setConfirmation(null);
                    $customer1->save();
                } catch (\Exception $e) {
                    $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                }
            }
            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
            $this->session->setCustomerDataAsLoggedIn($customer);
            $this->messageManager->addSuccess(__('Login successful.'));
            $this->session->regenerateId(); 
            $this->_eventManager->dispatch('customer_data_object_login', ['customer' => $customer]);
            $this->_eventManager->dispatch('customer_login', ['customer' => $customer1]);

            /** VISITOR */
            $visitor = $this->visitor;
            $visitor->setData($this->session->getVisitorData());
            $visitor->setLastVisitAt((new \DateTime())->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT));
            $visitor->setSessionId($this->session->getSessionId());
            $visitor->save();
            $this->_eventManager->dispatch('visitor_init', ['visitor' => $visitor]);
            $this->_eventManager->dispatch('visitor_activity_save', ['visitor' => $visitor]);
            echo "<script type=\"text/javascript\">window.close();".$link_redirect."</script>"; 
            exit;
        }
        if ($dataUser) {
            $data['id']                    = $dataUser['identifier'];
            $data['email']                 = $dataUser['email']; 
            $data['password']              =  $this->socialHelper->createPassword();
            $data['password_confirmation'] = $data['password'];
            $data['first_name']            = $dataUser['firstName'];
            $data['last_name']             = $dataUser['lastName'];
            $data['picture']               = $dataUser['photoURL'];

            $store_id   = $this->storeManager->getStore()->getStoreId();
            $website_id = $this->storeManager->getStore()->getWebsiteId();
            $customer   = $this->socialHelper->getCustomerByEmail($data['email'], $website_id);
            if (!$customer || !$customer->getId()) {
                $customer = $this->socialHelper->createCustomerMultiWebsite($data, $website_id, $store_id);
                if ($this->socialHelper->sendPassword()) {
                    try {
                        $this->accountManagement->sendPasswordReminderEmail($customer);
                    } catch (Exception $e) {
                        $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                    }
                }
            }
            $this->setAuthorCustomer($data['id'], $customer->getId(), $data);
            $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());
            if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());
                // @codingStandardsIgnoreStart
                $this->messageManager->addSuccess(
                    __( 
                        'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                        $email
                    )
                );
            } else {
                $this->session->setCustomerDataAsLoggedIn($customer);
                $this->messageManager->addSuccess(__('Login successful.'));
                $this->session->regenerateId();
            }
            echo "<script type=\"text/javascript\">window.close();".$link_redirect."</script>"; 
        }
    }

    public function getCustomerIdByGoogleId($googleId)
    {
        $customer = $this->googleCustomerCollectionFactory->create();
        $dataUser     = $customer
        ->addFieldToFilter('social_id', $googleId)
        ->addFieldToFilter('type', self::SOCIAL_TYPE)
        ->getFirstItem();
        if ($dataUser && $dataUser->getId()) {
            return $dataUser->getCustomerId();
        } else {
            return null;
        }
    }

    public function setAuthorCustomer($googleId, $customerId, $data = [])
    {
        $googleCustomer = $this->googleCustomerModelFactory->create();
        $googleCustomer->setData('social_id', $googleId);
        $googleCustomer->setData('username', $googleId);
        $googleCustomer->setData('customer_id', $customerId);
        $googleCustomer->setData('type', self::SOCIAL_TYPE);

        if (isset($data['picture'])) {
            $googleCustomer->setData('picture', $data['picture']);
        }
        $googleCustomer->setData('is_send_password_email', $this->socialHelper->sendPassword());
        try {
            $googleCustomer->save();
        } catch (Exception $e) {
        }

        return;
    }

    /**
     * Retrieve cookie manager
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\PhpCookieManager
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\PhpCookieManager::class
                );
        }
        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @deprecated
     * @return \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory::class
                );
        }
        return $this->cookieMetadataFactory;
    }

    public function getGoogleHelper()
    {
        $objectmanager     = \Magento\Framework\App\ObjectManager::getInstance();
        $helper = $objectmanager->create('Lof\SocialLogin\Helper\Google\Data');

        return $helper;
    }

}
