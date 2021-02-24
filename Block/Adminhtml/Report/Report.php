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

namespace Lof\SocialLogin\Block\Adminhtml\Report;

use Lof\SocialLogin\Model\Config;

class Report extends \Magento\Framework\View\Element\Template
{

 
    /**
     * @var \Lof\SocialLogin\Model\Social
     */
    protected $_social;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Lof\SocialLogin\Model\Social           $socialCollection
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Lof\SocialLogin\Model\Social $socialCollection
    ) {
        $this->_social = $socialCollection;
        parent::__construct($context);
    }

    /**
     * @return Lof\SocialLogin\Model\ResourceModel\Social\Collecion
     */
    public function getSumSocial()
    {
        $social = $this->_social->getCollection();
        return $social;
    }
    
    /**
     * @return array
     */
    public function getlistSocialNetworks()
    {
        return Config::getSocialNetworks();
    }
}
