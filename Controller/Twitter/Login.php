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
use Lof\SocialLogin\Model\Twitter;
use Lof\SocialLogin\Helper\Twitter\Data as SocialHelper;

class Login extends Action
{

  
    protected $twitter;
    protected $socialHelper;

  /**
   * @param Context      $context
   * @param Twitter      $twitter
   * @param SocialHelper $socialHelper
   */
    public function __construct(
        Context $context,
        Twitter $twitter,
        SocialHelper $socialHelper
    ) {
        parent::__construct($context);
        $this->twitter                          = $twitter;
        $this->socialHelper                      = $socialHelper;
    }

    public function execute()
    {
        $connection = $this->twitter->newLoginTwitter();
        $request_token = $connection->oauth('oauth/request_token', ['oauth_callback' => $this->socialHelper->getAuthUrl()]);

        if ($request_token) {
            $_SESSION['oauth_token'] = $request_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

            $url = $connection->url('oauth/authorize', ['oauth_token' => $request_token['oauth_token']]);
            echo "<script type=\"text/javascript\">window.location.href = '".$url."';</script>";
        } else {
            var_dump($request_token);
            exit('Error getting request_token');
        }
    }
}
