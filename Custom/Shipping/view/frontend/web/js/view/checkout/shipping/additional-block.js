define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'knockout'
], function (Component,quote,ko) {
    'use strict';

    var shipping_options = window.checkoutConfig.shipping_options;
    var shipping_options_title = window.checkoutConfig.shipping_options_title;
    var ignored_shipping_methods = window.checkoutConfig.ignored_shipping_methods;

    return Component.extend({
        defaults: {
            template: 'Custom_Shipping/checkout/shipping/additional-block',
            shipping_options: shipping_options,
            title: shipping_options_title,
            ignored_shipping_methods: ignored_shipping_methods,
            shouldDisplay: ko.observable(!quote.isVirtual())
        },
        initialize: function() {
            this._super();
            quote.shippingMethod.subscribe((function(shippingMethod) {
                let shippingMethodcode = shippingMethod.carrier_code + "_" + shippingMethod.method_code;
                let ignoredShippingMethod = this.ignored_shipping_methods.indexOf(shippingMethodcode) !== -1;
                this.shouldDisplay(!ignoredShippingMethod && !quote.isVirtual());
            }).bind(this));
        },
        getShippingOptions: function(){
        	return shipping_options;
        }
    });
});
