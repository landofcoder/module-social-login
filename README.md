# Social Login for Magento 2

The module allow customer login the site with social accounts like Facebook, Twitter, Google Email

# The module require setup library before to use

Try to setup the social login library via composer:
```
composer require landofcoder/module-social-login-lib
```

# How to setup module

1. Upload module file into folder app/code/Lof/SocialLogin

2. Run magento 2 commands:
```
php bin/magento setup:upgrade --keep-generated
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
```

3. Go to admin > Stores > Configuration > Landofcoder - Extensions > Social Login to config the module

# How to get callback url on module to config Social Application?

When you config social application, maybe require call back url / call back uri. The module are using callback link with format as this:

https://your-site-domain/lofsociallogin/[social_login_type]/callback

Example:
- Facebook callback link: https://your-site-domain/lofsociallogin/facebook/callback
- Instagram callback link: https://your-site-domain/lofsociallogin/instagram/callback
- Wordpress callback link: https://your-site-domain/lofsociallogin/wordpress/callback
- Pinterest callback link: https://your-site-domain/lofsociallogin/pinterest/callback

## Donation

If this project help you reduce time to develop, you can give me a cup of coffee :) 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/allorderdesk)


**Our Magento 2 Extensions List**
* [Megamenu for Magento 2](https://landofcoder.com/magento-2-mega-menu-pro.html/)

* [FREE Page Builder for Magento 2](https://landofcoder.com/magento-2-page-builder.html/)

* [Magento 2 Marketplace - Multi Vendor Extension](https://landofcoder.com/magento-2-marketplace-extension.html/)

* [Magento 2 Multi Vendor Mobile App Builder](https://landofcoder.com/magento-2-multi-vendor-mobile-app.html/)

* [Magento 2 Form Builder](https://landofcoder.com/magento-2-form-builder.html/)

* [Magento 2 Reward Points](https://landofcoder.com/magento-2-reward-points.html/)

* [Magento 2 Flash Sales - Private Sales](https://landofcoder.com/magento-2-flash-sale.html)

* [Magento 2 B2B Packages](https://landofcoder.com/magento-2-b2b-extension-package.html)

* [Magento 2 One Step Checkout](https://landofcoder.com/magento-2-one-step-checkout.html/)

* [Magento 2 Customer Membership](https://landofcoder.com/magento-2-membership-extension.html/)

* [Magento 2 Checkout Success Page](https://landofcoder.com/magento-2-checkout-success-page.html/)


**Featured Magento Services**

* [Customization Service](https://landofcoder.com/magento-2-create-online-store/)

* [Magento 2 Support Ticket Service](https://landofcoder.com/magento-support-ticket.html/)

* [Magento 2 Multi Vendor Development](https://landofcoder.com/magento-2-create-marketplace/)

* [Magento Website Maintenance Service](https://landofcoder.com/magento-2-customization-service/)

* [Magento Professional Installation Service](https://landofcoder.com/magento-2-installation-service.html)

* [Customization Service](https://landofcoder.com/magento-customization-service.html)

