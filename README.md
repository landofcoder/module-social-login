# Social Login for Magento 2

The module allow customer login the site with social accounts like Facebook, Twitter, Google Email

# The module require setup library before to use

Try to setup the social login library via composer:
```
composer require landofcoder/module-social-login-lib
```

# Ho to setup module

1. Upload module file into folder app/code/Lof/SocialLogin

2. Run magento 2 commands:
```
php bin/magento setup:upgrade --keep-generated
php bin/magento setup:static-content:deploy -f
php bin/magento cache:clean
```

3. Go to admin > Stores > Configuration > Landofcoder - Extensions > Social Login to config the module