<?php
namespace Lof\SocialLogin\Controller\Facebook;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Lof\SocialLogin\Model\FacebookApp;
use Magento\Store\Model\StoreManagerInterface;
use Lof\SocialLogin\Helper\Facebook\Data as DataHelper;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Session;
use Symfony\Component\Config\Definition\Exception\Exception;
use Lof\SocialLogin\Model\SocialFactory as FacebookModelFactory;
use Lof\SocialLogin\Model\ResourceModel\Social\CollectionFactory as FacebookCollectionFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Visitor;
use Lof\SocialLogin\Model\Facebook\Facebook; 

class Callback extends Action
{
    const SOCIAL_TYPE = 'facebook';
    protected $facebook;
    protected $dataHelper;
    protected $accountManagement;
    protected $customerUrl;
    protected $session;
    protected $facebookCustomerCollectionFactory;
    protected $facebookCustomerModelFactory;
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
        FacebookApp $facebook,
        StoreManagerInterface $storeManager,
        DataHelper $dataHelper,
        AccountManagementInterface $accountManagement,
        CustomerUrl $customerUrl,
        FacebookModelFactory $facebookCustomerModelFactory,
        FacebookCollectionFactory $facebookCustomerCollectionFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        Visitor $visitor
        ) {
        parent::__construct($context);
        $this->facebook                          = $facebook;
        $this->storeManager                      = $storeManager;
        $this->dataHelper                        = $dataHelper;
        $this->accountManagement                 = $accountManagement;
        $this->customerUrl                       = $customerUrl;
        $this->session                           = $customerSession;
        $this->facebookCustomerModelFactory      = $facebookCustomerModelFactory;
        $this->facebookCustomerCollectionFactory = $facebookCustomerCollectionFactory;
        $this->customerFactory                   = $customerFactory;
        $this->customerRepository                = $customerRepository;
        $this->visitor                           = $visitor;
    }

    public function execute()
    {
        $redirect = $this->dataHelper->getConfig('general/redirect_page');
        if(empty($redirect)){
            $link_redirect = "window.opener.location.reload();";
        }else{
            $link_redirect = "window.opener.location= '".$redirect."';";
        };
        $fb = $this->facebook->newFacebook();
        if($fb && is_object($fb)) {
            $helper = $fb->getRedirectLoginHelper();
            $_SESSION['FBRLH_state']=$_GET['state'];
            try {
              $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
              // When Graph returns an error
              echo 'Graph returned an error: ' . $e->getMessage();
              exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
              // When validation fails or other local issues
              echo 'Facebook SDK returned an error: ' . $e->getMessage();
              exit;
            }

            if (!isset($accessToken)) {
              if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
              } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
              }
              exit;
            } 
            $response = $fb->get('/me?fields=id,name,email,first_name,last_name', $accessToken->getValue()); 
            $dataUser = $response->getdecodedBody(); 
            $data = [];
             
            $customerId = $this->getCustomerIdByFacebookId($dataUser['id']);
            if ($customerId) {
                $customer = $this->customerRepository->getById($customerId);
                $customer1 = $this->customerFactory->create()->load($customerId);
                if ($customer->getConfirmation()) {
                    try {
                        $customer1->setConfirmation(true);
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
                $this->messageManager->addSuccess(__('Login successful.'));
                $this->session->regenerateId();
                $this->session->setCustomerDataAsLoggedIn($customer);
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
                if (isset($dataUser['email'])) {
                    $data['id'] = $dataUser['id'];
                    $data['email']= $dataUser['email'];
                    $data['password'] =  $this->dataHelper->createPassword();
                    $data['password_confirmation'] = $data['password'];
                    $data['first_name'] = $dataUser['first_name'];
                    $data['last_name']  = $dataUser['last_name'];
                } else {
                    $data['id'] = $dataUser['id'];
                    $data['email']= $dataUser['id'].'@facebook.com';
                    $data['password'] =  $this->dataHelper->createPassword();
                    $data['password_confirmation'] = $data['password'];
                    $data['first_name'] = $dataUser['first_name'];
                    $data['last_name']  = $dataUser['last_name'];
                }
                $store_id   = $this->storeManager->getStore()->getStoreId();
                $website_id = $this->storeManager->getStore()->getWebsiteId();
                $customer   = $this->dataHelper->getCustomerByEmail($data['email'], $website_id);
                if (!$customer || !$customer->getId()) {
                    $customer = $this->dataHelper->createCustomerMultiWebsite($data, $website_id, $store_id);
                    if ($this->dataHelper->sendPassword()) {
                        try {
                            $this->accountManagement->sendPasswordReminderEmail($customer);
                        } catch (Exception $e) {
                            $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
                        }
                    }
                }
                $this->setAuthorCustomer($data['id'], $customer->getId());
                $confirmationStatus = $this->accountManagement->getConfirmationStatus($customer->getId());

                if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
                    $email = $this->customerUrl->getEmailConfirmationUrl($customer->getEmail());
                    // @codingStandardsIgnoreStart
                    $this->messageManager->addSuccess(
                        __(
                            'You must confirm your account. Please check your email for the confirmation link or <a href="%1">click here</a> for a new link.',
                            $email
                        )
                    );
                } else {
                    //if($customer instanceof \Magento\Customer\Api\Data\CustomerInterface) {
                        $this->session->setCustomerDataAsLoggedIn($customer);
                        $this->messageManager->addSuccess(__('Login successful.'));
                        $this->session->regenerateId();
                    //}
                }
                echo "<script type=\"text/javascript\">window.close();".$link_redirect."</script>"; 
            }
        } else {
            echo "<script type=\"text/javascript\">window.close();".$link_redirect."</script>"; 
                exit;
        }
    }

    public function setAuthorCustomer($facebookId, $customerId)
    {
        $facebookCustomer = $this->facebookCustomerModelFactory->create();
        $facebookCustomer->setData('social_id', $facebookId);
        $facebookCustomer->setData('customer_id', $customerId);
        $facebookCustomer->setData('type', self::SOCIAL_TYPE);
        $facebookCustomer->setData('is_send_password_email', $this->dataHelper->sendPassword());
        try {
            $facebookCustomer->save();
        } catch (Exception $e) {
            $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
        }

        return;
    }

    public function getCustomerIdByFacebookId($facebookId)
    {
        $customer = $this->facebookCustomerCollectionFactory->create();
        $user     = $customer
        ->addFieldToFilter('social_id', $facebookId)
        ->addFieldToFilter('type', self::SOCIAL_TYPE)
        ->getFirstItem();
        if ($user && $user->getId()) {
            return $user->getCustomerId();
        } else {
            return null;
        }
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
}
