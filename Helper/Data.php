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
namespace Lof\SocialLogin\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;
use Lof\SocialLogin\Model\Social;
use Lof\SocialLogin\Model\Config;
use Magento\Customer\Api\AccountManagementInterface;

class Data extends AbstractHelper
{
    const XML_PATH_GENERAL_ENABLED                = 'sociallogin/general/is_enabled';
    const XML_PATH_GENERAL                        = 'sociallogin/general/';
    const XML_PATH_GENERAL_POPUP_LEFT             = 'sociallogin/general/left';
    const XML_PATH_GENERAL_STYLE_MANAGEMENT       = 'sociallogin/general/style_management';
    const XML_PATH_GENERAL_SOCIAL_POSITION        = 'sociallogin/general/social_position';
    const XML_PATH_SECURE_IN_FRONTEND             = 'web/secure/use_in_frontend';
    const XML_PATH_AMAZON_STYLE_MANAGEMENT        = 'sociallogin/amazon/style_management';
    const XML_PATH_DISQUS_STYLE_MANAGEMENT        = 'sociallogin/disqus/style_management';
    const XML_PATH_DROPBOX_STYLE_MANAGEMENT       = 'sociallogin/dropbox/style_management';
    const XML_PATH_FACEBOOK_STYLE_MANAGEMENT      = 'sociallogin/facebook/style_management';
    const XML_PATH_GITHUB_STYLE_MANAGEMENT        = 'sociallogin/github/style_management';
    const XML_PATH_GOOGLE_STYLE_MANAGEMENT        = 'sociallogin/google/style_management';
    const XML_PATH_INSTAGRAM_STYLE_MANAGEMENT     = 'sociallogin/instagram/style_management';
    const XML_PATH_LINKEDIN_STYLE_MANAGEMENT      = 'sociallogin/linkedin/style_management';
    const XML_PATH_PAYPAL_STYLE_MANAGEMENT        = 'sociallogin/paypal/style_management';
    const XML_PATH_PINTEREST_STYLE_MANAGEMENT     = 'sociallogin/pinterest/style_management';
    const XML_PATH_SOUNDCLOUD_STYLE_MANAGEMENT    = 'sociallogin/soundcloud/style_management';
    const XML_PATH_STACKOVERFLOW_STYLE_MANAGEMENT = 'sociallogin/stackoverflow/style_management';
    const XML_PATH_TWITTER_STYLE_MANAGEMENT       = 'sociallogin/twitter/style_management';
    const XML_PATH_VIMEO_STYLE_MANAGEMENT         = 'sociallogin/vimeo/style_management';
    const XML_PATH_WORDPRESS_STYLE_MANAGEMENT     = 'sociallogin/wordpress/style_management';
    const XML_PATH_GOOGLE_CLIENT_ID               = 'sociallogin/google/client_id';
    const XML_PATH_WINDOWSLIVE_STYLE_MANAGEMENT   = 'sociallogin/windowslive/style_management';
    const XML_PATH_FOURSQUARE_STYLE_MANAGEMENT    = 'sociallogin/foursquare/style_management';
    const XML_PATH_TWITCH_STYLE_MANAGEMENT        = 'sociallogin/twitch/style_management';
    const XML_PATH_SLACK_STYLE_MANAGEMENT         = 'sociallogin/slack/style_management';
    const XML_PATH_WEIBO_STYLE_MANAGEMENT         = 'sociallogin/weibo/style_management';
    const XML_PATH_WECHAT_STYLE_MANAGEMENT        = 'sociallogin/wechat/style_management';

    protected $customerFactory;
    protected $storeManager;
    protected $objectManager;
    protected $_social;

    /**
     * @param Context                $context
     * @param ObjectManagerInterface $objectManager
     * @param CustomerFactory        $customerFactory
     * @param StoreManagerInterface  $storeManager
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        CustomerInterfaceFactory $customerFactory,
        Session $customerSession,
        Social $social,
        AccountManagementInterface $accountManagement,
        StoreManagerInterface $storeManager
    ) {
        $this->objectManager     = $objectManager;
        $this->customerFactory   = $customerFactory;
        $this->storeManager      = $storeManager;
        $this->_customerSession  = $customerSession;
        $this->_social           = $social;
        $this->accountManagement = $accountManagement;
        parent::__construct($context);
    }

    public function getConfig($key, $store = null)
    {
        $store = $this->storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();

        $result = $this->scopeConfig->getValue(
            'sociallogin/'.$key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $result;
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL_ENABLED, $storeId);
    }
    /**
     * @param  $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GENERAL . $code, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getPopupEffect($storeId = null)
    {
        return $this->getGeneralConfig('popup_effect', $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getStyleManagement($storeId = null)
    {
        $style = $this->getGeneralConfig('style_management', $storeId);
        return $style;
    }

    public function getSocialPosition($storeId = null)
    {
        $postion = $this->getGeneralConfig('social_position', $storeId);
        return $postion;
    }
    public function getCustomCss($storeId = null)
    {
        return $this->getGeneralConfig('custom_css', $storeId);
    }

    /**
     * @param string $email
     * @return bool|\Magento\Customer\Model\Customer
     */
    public function getCustomerByEmail($email, $websiteId = null)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->objectManager->create(
            'Magento\Customer\Model\Customer'
        );
        if (!$websiteId) {
            $customer->setWebsiteId($this->storeManager->getWebsite()->getId());
        } else {
            $customer->setWebsiteId($websiteId);
        }
        $customer->loadByEmail($email);

        if ($customer->getId()) {
            return $customer;
        }

        return false;
    }
    public function getCustomerById($id, $websiteId = null)
    {
        /** @var \Magento\Customer\Model\Customer $customer */
        $customer = $this->objectManager->create(
            'Magento\Customer\Model\Customer'
        );
        if (!$websiteId) {
            $customer->setWebsiteId($this->storeManager->getWebsite()->getId());
        } else {
            $customer->setWebsiteId($websiteId);
        }
        $customer->load($id);

        if ($customer->getId()) {
            return $customer;
        }

        return false;
    }


    /**
     * @param $data
     * @param $website_id
     * @param $store_id
     * @return mixed
     */
    public function createCustomerMultiWebsite($data, $website_id, $store_id)
    {
        $customer = $this->customerFactory->create();
        $customer->setFirstname($data['first_name'])
        ->setLastname($data['last_name'])
        ->setEmail($data['email'])
        ->setWebsiteId($website_id)
        ->setStoreId($store_id);

        $customer = $this->accountManagement->createAccount($customer, $data['password']);

        return $customer;
    }

    public function isSecure()
    {
        $isSecure = $this->getConfigValue(self::XML_PATH_SECURE_IN_FRONTEND);

        return $isSecure;
    }

    public function getEditUrl()
    {
        $isSecure = $this->isSecure();

        return $this->_getUrl('customer/account/edit', ['_secure' => $isSecure]);
    }

    public function getCustomer(){
        $customer = $this->_customerSession->getCustomer();
        $socialCustomer = $this->_social->getCollection()->addFieldToFilter('customer_id', $customer->getId())->getFirstItem();
        if($socialCustomer && $socialCustomer->getId()){
            $customer->setData('type', $socialCustomer->getType());
            $customer->setData('social_id', $socialCustomer->getSocialId());
            $customer->setData('social_username', $socialCustomer->getUsername());
            $customer->setData('picture', $socialCustomer->getPicture());
        }
        return $this->_customerSession->getCustomer();
    }

    public function getCustomerPhoto($customer)
    {
        $type        = $customer->getType();
        $socialId    = $customer->getSocialId();
        $socialEmail = $customer->getSocialUsername();
        $photoUrl    = '';
        switch ($type) {
            case Config::FACEBOOK:
            $photoUrl = 'http://graph.facebook.com/' . $socialId . '/picture?type=small';
            break;
            case Config::GOOGLE:
            $photoUrl = $customer->getPicture();
            break;
            case Config::TWITTER:
            $photoUrl = 'https://twitter.com/' . str_replace('@twitter.com', '', $socialEmail) . '/profile_image?size=mini';
            break;
        }
        return $photoUrl;
    }

    public function createPassword()
    {
        $password = base64_encode(rand().time());
        return $password;
    }

    public function getStyleColorAmazon($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_AMAZON_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorDisqus($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_DISQUS_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorFacebook($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FACEBOOK_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorDropbox($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_DROPBOX_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorGithub($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GITHUB_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorGoogle($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_GOOGLE_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorInstagram($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_INSTAGRAM_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorLinkedin($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_LINKEDIN_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorPaypal($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PAYPAL_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorPinterest($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_PINTEREST_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorSoundcloud($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SOUNDCLOUD_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorStackoverflow($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_STACKOVERFLOW_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorTwitter($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITTER_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorVimeo($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_VIMEO_STYLE_MANAGEMENT, $storeId);
    }

    public function getStyleColorWordpress($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_WORDPRESS_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorWindowslive($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_WINDOWSLIVE_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorFoursquare($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_FOURSQUARE_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorTwitch($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_TWITCH_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorSlack($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SLACK_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorWeibo($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_WEIBO_STYLE_MANAGEMENT, $storeId);
    }
    public function getStyleColorWechat($storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_WECHAT_STYLE_MANAGEMENT, $storeId);
    }
}
