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
namespace Lof\SocialLogin\Block\Adminhtml\Edit;

use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Controller\RegistryConstants;
use \Magento\Framework\ObjectManagerInterface;

class Tab extends \Magento\Backend\Block\Widget\Tab
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $_customerFacetory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Backend\Block\Template\Context   $context
     * @param \Magento\Framework\Registry               $registry
     * @param CustomerFactory                           $customerFacetory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerFactory $customerFacetory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->_coreRegistry     = $registry;
        $this->_customerFacetory = $customerFacetory;
        $this->_objectManager    = $objectManager;
        parent::__construct($context, $data);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        $customer = $this->_customerFacetory->create()->load($this->getCustomerId());
        return $customer;
    }

    /**
     * @return array|null
     */
    public function getDataSocial()
    {
        $customer_id = $this->getCustomerId();
        $model = $this->_objectManager->create('Lof\SocialLogin\Model\Social');
        $model->loadByAttribute('customer_id', $customer_id);
        $data = $model->getData();
        return $data;
    }

    /**
     * @return string|null
     */
    public function getLinkSocial()
    {
        $data = $this->getDataSocial();
        $link = '#';
        if ($data) {
            $dataCustomer = $this->getCustomer();
            switch ($data['type']) {
                case 'instagram':
                    $link = 'https://www.instagram.com/'.$data['username'];
                    break;
                case 'facebook':
                    $link = 'https://www.facebook.com/'.$data['username'];
                    break;
                case 'twitter':
                    $link = 'https://twitter.com/'.$data['username'];
                    break;
                case 'google':
                    $link = 'https://plus.google.com/u/0/'.$data['username'];
                    break;
                case 'wordpress':
                    $link = 'https://gravatar.com/'.$data['username'];
                    break;
                case 'linkedin':
                    $link = $data['username'];
                    break;
                case 'github':
                    $link = $data['username'];
                    break;
                case 'paypal':
                    $link = $data['username'];
                    break;
                case 'disqus':
                    $link = $data['username'];
                    break;
                case 'amazon':
                    $link = $data['username'];
                    break;
                case 'pinterest':
                    $link = $data['username'];
                    break;
                case 'stackoverflow':
                    $link = $data['username'];
                    break;
                case 'vimeo':
                    $link = $data['username'];
                    break;
                case 'dropbox':
                    $link = $data['username'];
                    break;
                case 'soundcloud':
                    $link = $data['username'];
                    break;
                default:
                    $link = '#';
                    break;
            }
        }
        return $link;
    }
}
