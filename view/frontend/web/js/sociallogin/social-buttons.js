define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'lof/sociallogin'
    ],
    function ($, ko, Component, SocialLoginPopup) {
        'use strict';

        ko.bindingHandlers.socialButton = {
            init: function (element, valueAccessor, allBindings) { 
                var popup = new SocialLoginPopup();
                jQuery(element).on('click', function () { 
                    popup.openPopup(allBindings.get('url'), allBindings.get('label'));
                }); 
            }
        };

        return Component.extend({
            defaults: {
                template: 'Lof_SocialLogin/social-buttons'
            },
            buttonLists: window.socialAuthenticationPopup,

            socials: function () { 
                var socials = [];

                $.each(this.buttonLists, function (key, social) {
                    socials.push(social);
                }); 
                return socials;
            },

            isActive: function () {
                return (typeof this.buttonLists !== 'undefined');
            }
        });
    }
);
