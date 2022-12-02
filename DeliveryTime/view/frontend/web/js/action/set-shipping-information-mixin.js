/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper, quote) {
    'use strict';

    return function (setShippingInformationAction) {

        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress();

            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            if (shippingAddress['customAttributes'] === undefined) {
                shippingAddress['customAttributes'] = {};
            }
            if(
                shippingAddress['extension_attributes']['delivery_date'] === undefined ||
                shippingAddress['extension_attributes']['delivery_time_interval'] === undefined ||
                shippingAddress['extension_attributes']['delivery_comment'] === undefined
            )
            {
                shippingAddress['customAttributes'] = shippingAddress['customAttributes'].concat({
                    attribute_code: "delivery_date", value: 'Delivery Date: '+$('#delivery_date').val()
                },{
                    attribute_code: "delivery_time_interval", value: 'Delivery Time: '+$('#delivery_time_interval').val()
                },{
                    attribute_code: "delivery_comment", value: 'Delivery Comment: '+$('#delivery_comment').val()
                });
            }
            shippingAddress['extension_attributes']['delivery_date'] = $('#delivery_date').val();
            shippingAddress['extension_attributes']['delivery_time_interval'] = $('#delivery_time_interval').val();
            shippingAddress['extension_attributes']['delivery_comment'] = $('#delivery_comment').val();
            // pass execution to original action ('Magento_Checkout/js/action/set-shipping-information')
            return originalAction();
        });
    };
});
