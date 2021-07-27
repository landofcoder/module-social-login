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
namespace Lof\SocialLogin\Block\SocialLogin; 
use Lof\SocialLogin\Block\SocialLogin;

class Social extends SocialLogin
{ 
    public function isEnabled()
    {
        if ($this->helperData()->isEnabled()) {
            return true;
        }
        return false;
    } 



    /**
     * @return array
     */
    public function getAvailableSocials()
    { 
        $availableSocials = json_decode($this->helperData->getSocialPosition(), true); 
        $listSocials = array();
        foreach ($availableSocials as $key => $value) { 
            array_push($listSocials, $value['id']);
        }   

        return $listSocials;
    }

 
    /**
     * @return array
     */
    public function getSocialButtonsConfig()
    {
        $availableButtons = $this->getAvailableSocials();
        $list = array();
        foreach ($availableButtons as $socialKey => $socialLabel) { 
            $list[$socialLabel] = [
                    'label'     => $socialLabel,
                    'login_url' => $this->getLoginUrl($socialLabel),
                    'title'     => $this->helperData->getConfigValue('sociallogin/' . $socialLabel . '/title_social')
                ];
        }      
 
        return $list;
    }



    /**
     * @return \Lof\SocialLogin\Helper\Data
     */
    protected function helperData()
    {
        return $this->objectManager->create('Lof\SocialLogin\Helper\Data');
    } 
 
    /**
     * @return Lof\SocialLogin\Model\Amazon
     */
    protected function getModel($social)
    { 
        return $this->objectManager->create('Lof\SocialLogin\Model\\'.$social);
    }

    /**
     * retrive form login url
     * @return string
     */
    public function getAmazonLoginUrl()
    {
        return $this->getModel('Amazon')->getAmazonLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getDisqusLoginUrl()
    {
        return $this->getModel('Disqus')->getDisqusLoginUrl();
    }
 

    /**
     * retrive form login url
     * @return string
     */
    public function getDropboxLoginUrl()
    {
        return $this->getModel('Dropbox')->getDropboxLoginUrl();
    }
 
    /**
     * retrive form login url
     * @return string
     */
    public function getFacebookLoginUrl()
    {
        return $this->getModel('FacebookApp')->getFacebookLoginUrl();
    }
 
    /**
     * retrive form login url
     * @return string
     */
    public function getFoursquareLoginUrl()
    {
        return $this->getModel('Foursquare')->getFoursquareLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getGithubLoginUrl()
    {
        return $this->getModel('Github')->getGithubLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getGoogleLoginUrl()
    {
        $url = $this->getUrl('lofsociallogin/google/callback', ['_secure' => $this->isSecure()]);
        $url = str_replace("callback/", "callback", $url);
        return str_replace("/index.php", "", $url);
    } 
    /**
     * retrive form login url
     * @return string
     */
    public function getInstagramLoginUrl()
    {
        return $this->getModel('Instagram')->getInstagramLoginUrl();
    }
 
    /**
     * retrive form login url
     * @return string
     */
    public function getLinkedinLoginUrl()
    {
        return $this->getModel('Linkedin')->getLinkedinLoginUrl();
    }
 
    /**
     * retrive form login url
     * @return string
     */
    public function getPaypalLoginUrl()
    {
        return $this->getModel('Paypal')->getPaypalLoginUrl();
    } 
    /**
     * retrive form login url
     * @return string
     */
    public function getPinterestLoginUrl()
    {
        return $this->getModel('Pinterest')->getPinterestLoginUrl();
    } 
 
    /**
     * retrive form login url
     * @return string
     */
    public function getSlackLoginUrl()
    {
        return $this->getModel('Slack')->getSlackLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getSoundcloudLoginUrl()
    {
        return $this->getModel('Soundcloud')->getSoundcloudLoginUrl();
    } 
    /**
     * retrive form login url
     * @return string
     */
    public function getStackoverflowLoginUrl()
    {
        return $this->getModel('Stackoverflow')->getStackoverflowLoginUrl();
    } 
    /**
     * retrive form login url
     * @return string
     */
    public function getTwitchLoginUrl()
    {
        return $this->getModel('Twitch')->getTwitchLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getTwitterLoginUrl()
    {
        $url = $this->getUrl('lofsociallogin/twitter/login', ['_secure' => $this->isSecure()]);
        $url = str_replace("callback/", "callback", $url);
        return str_replace("/index.php", "", $url);
    }
 
    /**
     * retrive form login url
     * @return string
     */
    public function getVimeoLoginUrl()
    {
        return $this->getModel('Vimeo')->getVimeoLoginUrl();
    }

 
    /**
     * retrive form login url
     * @return string
     */
    public function getWechatLoginUrl()
    {
        return $this->getModel('Wechat')->getWechatLoginUrl();
    } 

    /**
     * retrive form login url
     * @return string
     */
    public function getWeiboLoginUrl()
    {
        return $this->getModel('Weibo')->getWeiboLoginUrl();
    } 
    /**
     * retrive form login url
     * @return string
     */
    public function getWindowsliveLoginUrl()
    {
        return $this->getModel('Windowslive')->getWindowsliveLoginUrl();
    }
 

    /**
     * retrive form login url
     * @return string
     */
    public function getWordpressLoginUrl()
    {
        return $this->getModel('Wordpress')->getWordpressLoginUrl();
    }


    public function getLoginUrl($value)
    {  
        if ($value) {
            switch ($value) {
                case 'amazon':
                return $this->getAmazonLoginUrl();
                break;
                case 'disqus':
                return $this->getDisqusLoginUrl();
                break;
                case 'dropbox':
                return $this->getDropboxLoginUrl();
                break;
                case 'facebook':
                return $this->getFacebookLoginUrl();
                break;
                case 'github':
                return $this->getGithubLoginUrl();
                break;
                case 'google':
                return $this->getGoogleLoginUrl();
                break;
                case 'instagram':
                return $this->getInstagramLoginUrl();
                break;
                case 'linkedin':
                return $this->getLinkedinLoginUrl();
                break;
                case 'paypal':
                return $this->getPaypalLoginUrl();
                break;
                case 'pinterest':
                return $this->getPinterestLoginUrl();
                break;
                case 'soundcloud':
                return $this->getSoundcloudLoginUrl();
                break;
                case 'stackoverflow':
                return $this->getStackoverflowLoginUrl(); 
                break;
                case 'twitter':
                return $this->getTwitterLoginUrl();
                break;
                case 'vimeo':
                return $this->getVimeoLoginUrl();
                break;
                case 'wordpress':
                return $this->getWordpressLoginUrl();
                break;
                case 'twitch':
                return $this->getTwitchLoginUrl();
                break; 
                case 'wechat':
                return $this->getWechatLoginUrl();
                break;
                case 'weibo':
                return $this->getWeiboLoginUrl();
                break;
                case 'slack':
                return $this->getSlackLoginUrl();
                break;
                case 'foursquare':
                return $this->getFoursquareLoginUrl();
                break;
                case 'windowslive':
                return $this->getWindowsliveLoginUrl();
                break; 
                default:
                return '#';
                break;
            }
        }
         
    }
}
