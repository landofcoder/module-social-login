var config = {
    map: {
        "*": {
            'lof/sociallogin': 'Lof_SocialLogin/js/sociallogin',
            'lof/sociallogin/popup': 'Lof_SocialLogin/js/jquery/jquery.magnific-popup.min',
            'lof/sociallogin/form': 'Lof_SocialLogin/js/sociallogin/form.min',
        }
    },
    paths: {
        "lof/sociallogin/popup": "Lof_SocialLogin/js/jquery/jquery.magnific-popup.min"
    },
    shim: {
        'lof/sociallogin/popup': {
            'deps': ['jquery']
        }
    }
};
