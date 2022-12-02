define(
    [
        'jquery',
        'ko',
        'uiComponent'
    ],
    function ($, ko, Component) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'TUTJunior_CollectShipping/collect-shipping-cb'
            },
            isRegisterNewsletter: true
        });
    }
);
