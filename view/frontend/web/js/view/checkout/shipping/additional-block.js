define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'knockout'
], function (Component,quote,ko) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Azra_ShippingOptions/checkout/shipping/additional-block',
            shipping_options: window.checkoutConfig.shipping_options,
            title: window.checkoutConfig.shipping_options_title,
            ignored_shipping_methods: window.checkoutConfig.ignored_shipping_methods,
            shouldDisplay: ko.observable(!quote.isVirtual())
        },

        initialize: function() {
            this._super();

            quote.shippingMethod.subscribe(function(shippingMethod) {
                let shippingMethodcode = shippingMethod.carrier_code + "_" + shippingMethod.method_code;
                let ignoredShippingMethod = this.ignored_shipping_methods.indexOf(shippingMethodcode) !== -1;

                this.shouldDisplay(!ignoredShippingMethod && !quote.isVirtual());
            }, this);
        },

        getShippingOptions: function(){
        	return this.shipping_options;
        }
    });
});
