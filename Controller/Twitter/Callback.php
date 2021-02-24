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

namespace Lof\SocialLogin\Controller\Twitter;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\CustomerFactory;
use Symfony\Component\Config\Definition\Exception\Exception;
use Lof\SocialLogin\Model\Twitter;
use Lof\SocialLogin\Helper\Twitter\Data as SocialHelper;
use Lof\SocialLogin\Model\SocialFactory as TwitterModelFactory;
use Lof\SocialLogin\Model\ResourceModel\Social\CollectionFactory as TwitterCollectionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Visitor;

class Callback extends Action
{
    const SOCIAL_TYPE = 'twitter';
    protected $resultPageFactory;
    protected $twitter;
    protected $dataHelper;
    protected $socialHelper;
    protected $accountManagement;
    protected $customerUrl;
    protected $session;
    protected $twitterCustomerCollectionFactory;
    protected $twitterCustomerModelFactory;
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
        Twitter $twitter,
        StoreManagerInterface $storeManager,
        SocialHelper $socialHelper,
        PageFactory $resultPageFactory,
        AccountManagementInterface $accountManagement,
        CustomerUrl $customerUrl,
        TwitterModelFactory $twitterCustomerModelFactory,
        TwitterCollectionFactory $twitterCustomerCollectionFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterface $customerRepository,
        Session $customerSession,
        Visitor $visitor
    ) {
        parent::__construct($context);
        $this->twitter                          = $twitter;
        $this->storeManager                     = $storeManager;
        $this->socialHelper                     = $socialHelper;
        $this->resultPageFactory                = $resultPageFactory;
        $this->accountManagement                = $accountManagement;
        $this->customerUrl                      = $customerUrl;
        $this->session                          = $customerSession;
        $this->twitterCustomerModelFactory      = $twitterCustomerModelFactory;
        $this->twitterCustomerCollectionFactory = $twitterCustomerCollectionFactory;
        $this->customerFactory                  = $customerFactory;
        $this->customerRepository               = $customerRepository;
        $this->visitor                          = $visitor;
    }

    public function execute()
    {
        $connection = $this->twitter->callbackTwitter();
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
        $_SESSION['access_token'] = $access_token;
        if ($access_token) {
            $dataUser = $this->twitter->getDataCallbackTwitter();
        }

        $redirect = $this->socialHelper->getConfig(('general/redirect_page'));
        if(empty($redirect)){
            $link_redirect = "window.opener.location.reload();";
        }else{
            $link_redirect = "window.opener.location= '".$redirect."';";
        };
        $dataUser = (array)$dataUser;
        $data = [];
        $customerId = $this->getCustomerIdByTwitterId($dataUser['id']);
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
            if (isset($dataUser['email']) && $dataUser['email']!='') {
                $data['id'] = $dataUser['id'];
                $data['email']= $dataUser['email'];
                $data['password'] =  $this->socialHelper->createPassword();
                $data['password_confirmation'] = $data['password'];
                $data['first_name'] = $dataUser['name'];
                $data['last_name']  = '.';
            } else {
              $data['id'] = $dataUser['id'];
              $data['email']= $dataUser['screen_name'].'@twitter.com';
              $data['password'] =  $this->socialHelper->createPassword();
              $data['password_confirmation'] = $data['password'];
              $data['first_name'] = $dataUser['name'];
              $data['last_name']  = '.';
          }
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
        $this->setAuthorCustomer($data['id'], $customer->getId(), $dataUser['screen_name']);
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
        echo "<script type=\"text/javascript\">window.close(); '".$link_redirect."';</script>";
    }
}

public function getCustomerIdByTwitterId($twitterId)
{
    $customer = $this->twitterCustomerCollectionFactory->create();
    $dataUser     = $customer
    ->addFieldToFilter('social_id', $twitterId)
    ->addFieldToFilter('type', self::SOCIAL_TYPE)
    ->getFirstItem();
    if ($dataUser && $dataUser->getId()) {
        return $dataUser->getCustomerId();
    } else {
        return null;
    }
}

public function setAuthorCustomer($twitterId, $customerId, $username)
{
    $twitterCustomer = $this->twitterCustomerModelFactory->create();
    $twitterCustomer->setData('social_id', $twitterId);
    $twitterCustomer->setData('username', $username);
    $twitterCustomer->setData('customer_id', $customerId);
    $twitterCustomer->setData('type', self::SOCIAL_TYPE);
    $twitterCustomer->setData('is_send_password_email', $this->socialHelper->sendPassword());
    try {
        $twitterCustomer->save();
    } catch (Exception $e) {
        $this->messageManager->addError(__('We can\'t process your request right now. Sorry, that\'s all we know.'));
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
}
