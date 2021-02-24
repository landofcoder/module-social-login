/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery'
], function (jQuery) {
    'use strict';
    var SocialLoginForm = class {
        constructor(data) { 
            this.dataObjConfig = {
                header: jQuery(data.header).first(),
                popup: jQuery(data.socialLoginPopup).first(), 
                socialLoad: 'social-login-load',
                error: 'error-msg',
                success: 'success-msg',
                socialLoginForm: jQuery(data.authentication).first(),
                socialLoginFormContent: jQuery(data.authenticationContent).first(),
                socialFormLogin: jQuery(data.socialFormLogin),
                btnAuthentication: jQuery(data.btnAuthentication).first(),
                btnCreate: jQuery(data.btnCreate).first(),
                btnRemind: jQuery(data.btnRemind).first(),
                formLoginUrl: data.formLoginUrl,
                socialLoginCreate: jQuery(data.socialLoginCreate).first(),
                socialLoginCreateContent: jQuery(data.socialLoginCreateContent).first(),
                socialFormCreate: jQuery(data.socialFormCreate),
                btnSocialFormCreate: jQuery(data.btnSocialFormCreate).first(),
                btnSocialFormCreateBack: jQuery(data.btnSocialFormCreateBack).first(),
                socialFormCreateUrl: data.socialFormCreateUrl,
                socialLoginForgot: jQuery(data.socialLoginForgot).first(),
                socialLoginForgotContent: jQuery(data.socialLoginForgotContent).first(),
                socialFormPassword: jQuery(data.socialFormPassword),
                btnSendForget: jQuery(data.btnSendForget).first(),
                btnbackForget: jQuery(data.btnbackForget).first(),  
                forgotFormUrl: data.forgotFormUrl
            }; 
            this.init(data);
        }

        init(config) {
            jQuery( document ).ready(function() {
                if (this.dataObjConfig.header) {
                    this.dataObjConfig.header.find('a').each(function (index, objs) {
                        jQuery(objs).click(function(e){
                            var obj = e.target;
                            if (obj.hasClassName('create')) {
                                this.currentForm = 'create';
                            } else {
                                this.currentForm = 'login';
                            }
                            if (this.currentForm == 'login') {
                                this.showLogin();
                                this.disableCreateForm();
                                this.disableForgot();
                            } else if (this.currentForm == 'create') {
                                this.dataObjConfig.socialLoginForm.hide();
                                this.enableCreate();
                                this.disableForgot();
                            }
                        }.bind(this));
                    }.bind(this));
                }
            }.bind(this)); 

            jQuery(document).keypress(function(e) { 
                var keycode = event.keyCode || event.which;
                if(keycode == 13) {
                    if (this.currentForm == 'login') {
                        this.login();
                    } else if (this.currentForm == 'create') {
                        this.create();
                    } else if (this.currentForm == 'forgot') {
                        this.forgot();
                    }
                }
            }.bind(this));

            this.dataObjConfig.btnAuthentication.click(function () { 
                this.login();
            }.bind(this));

            this.dataObjConfig.btnSocialFormCreateBack.click( function () {
                this.currentForm = 'login';
                this.disableCreateForm();
                this.showLogin();
            }.bind(this));

            this.dataObjConfig.btnSendForget.click( function () {
                this.forgot();
            }.bind(this));

            this.dataObjConfig.btnbackForget.click( function () {
                this.currentForm = 'login';
                this.disableForgot();
                this.showLogin();
            }.bind(this));

            if (this.dataObjConfig.header) {
                this.dataObjConfig.header.find('a').each(function (index, obj) {
                    console.log(jQuery(obj).attr('href').includes('/customer/account/login/'));
                    if (obj) {
                        if(jQuery(obj).attr('href').includes('/customer/account/login/') 
                            || jQuery(obj).attr('href').includes('/wishlist/')
                            || jQuery(obj).attr('href').includes('/customer/account/')){
                                jQuery(obj).addClass('social-login');  
                                console.log(jQuery(obj));
                                if(jQuery(obj).attr('href').includes('/customer/account/create')){
                                    jQuery(obj).addClass('create');
                                }
                                console.log(this.dataObjConfig);
                                jQuery(obj).attr('href', config.socialLoginPopup)
                            }
                        this.currentForm = 'login';
                        obj.setAttribute('data-effect', config.popupEffect); 
                    }
                }.bind(this));
            }
            this.dataObjConfig.btnCreate.click( function () {
                this.currentForm = 'create';
                this.disableLogin();
                this.enableCreate();
            }.bind(this));

            this.dataObjConfig.btnRemind.click( function () {
                this.currentForm = 'forgot';
                this.disableLogin();
                this.enableForgot();
            }.bind(this));

            this.dataObjConfig.btnSocialFormCreate.click( function () {
                this.create();
            }.bind(this));
        }

        showLoadElement (block) { 
            jQuery('<div class='+this.dataObjConfig.socialLoad+'></div>').insertBefore(block);
        }

        removeLoading (block) {
            var selector = "." + this.dataObjConfig.socialLoad;
            block.css('position', ''); 
            jQuery('#social-login-popup').find(selector).each(function (index, element ) {
                element.remove();
            });

        }

        create () {
            if (this.dataObjConfig.socialFormCreate.valid()) {
                this.showLoadElement(this.dataObjConfig.socialLoginCreateContent);
                this.hideMessage(this.dataObjConfig.socialLoginCreateContent, this.dataObjConfig.error);
                var data = this.dataObjConfig.socialFormCreate.serialize(true);
                var dataObj = this.dataObjConfig;
                var self = this;
                jQuery.ajax({
                    url: this.dataObjConfig.socialFormCreateUrl,
                    type: 'POST',
                    data: data,
                    success: function (data, textStatus, xhr) {
                        var result = xhr.responseText.evalJSON();
                        if (result.success) {
                            self.showMessage(dataObj.socialLoginCreateContent, result.message, dataObj.success);
                            location.reload(true);
                        } else {
                            self.removeLoading(dataObj.socialLoginCreateContent); 
                            self.showMessage(dataObj.socialLoginCreateContent, result.message, dataObj.error);
                        }
                    }

                });
            }
        } 

        enableCreate() {
            this.dataObjConfig.socialLoginCreate.show();
        }

        disableCreateForm() {
            this.dataObjConfig.socialLoginCreate.hide();
        }

        showLogin() {
            this.dataObjConfig.socialLoginForm.show();
        }

        disableLogin() {
            this.dataObjConfig.socialLoginForm.hide();
        } 


        forgot () {
            if (this.dataObjConfig.socialFormPassword.valid()) {
                this.showLoadElement(this.dataObjConfig.socialLoginForgotContent);
                this.hideMessage(this.dataObjConfig.socialLoginForgotContent, this.dataObjConfig.error);
                this.hideMessage(this.dataObjConfig.socialLoginForgotContent, this.dataObjConfig.success);
                var data = this.dataObjConfig.socialFormPassword.serialize(true);
                var dataObj = this.dataObjConfig;
                var self = this;
                jQuery.ajax({
                    url: this.dataObjConfig.forgotFormUrl,
                    type: 'POST',
                    data: data,
                    success: function (data, textStatus, xhr) {
                        self.removeLoading(dataObj.socialLoginForgotContent);
                        var result = xhr.responseText.evalJSON();
                        if ( result.success ) {
                            self.showMessage( dataObj.socialLoginForgotContent, result.message, dataObj.success );
                        } else {
                            self.showMessage(dataObj.socialLoginForgotContent, result.message, dataObj.error);
                        }
                    }

                });
            }
        }                  

        enableForgot() {
            this.dataObjConfig.socialLoginForgot.show();
        }
        
        disableForgot() {
            this.dataObjConfig.socialLoginForgot.hide();
        }

        hideMessage (block, msgClass) { 
            jQuery('#social-login-popup').find('.' + msgClass).each(function (index, el) {
                console.log(el);
                el.remove();
            })
        }

        showMessage (block, message, msgClass) {
            if (typeof(message) === 'object' && message.length > 0) {
                message.each(function (msg) {
                    this.appendMsg(block, msg, msgClass);
                }.bind(this));
            } else if (typeof(message) === 'string') {
                this.appendMsg(block, message, msgClass);
            }
        }

        appendMsg (block, message, msgClass) {
            console.log(block);
            var currentMessage = null;
            var msgElm = block.find("." + msgClass + " ol");
            if (msgElm.length === 0) {
                jQuery('<div class='+msgClass+'><ol></ol></div>').insertBefore(block.find('.block-content').first());
            }
            jQuery("#social-login-popup ." + msgClass + " ol").append('<li>'+message+'</li>');
        }

        login() {
            if (this.dataObjConfig.socialFormLogin.valid()) {
                this.showLoadElement(this.dataObjConfig.socialLoginFormContent);
                this.hideMessage(this.dataObjConfig.socialLoginFormContent, this.dataObjConfig.error);
                var data = this.dataObjConfig.socialFormLogin.serialize(true);
                var dataObj = this.dataObjConfig;

                var self = this;

                jQuery.ajax({
                    url: this.dataObjConfig.formLoginUrl,
                    type: 'POST',
                    data: data,
                    success: function (data, textStatus, xhr) { 
                        var result = xhr.responseText.evalJSON();
                        if (result.success) {
                            self.showMessage(dataObj.socialLoginFormContent, result.message, dataObj.success);
                            window.location.reload(true);
                        } else { 
                            self.removeLoading(dataObj.socialLoginFormContent);
                            self.showMessage(dataObj.socialLoginFormContent, result.message, dataObj.error);
                        }
                    }

                });
            }
        }                                        
    }
    return SocialLoginForm;
});
