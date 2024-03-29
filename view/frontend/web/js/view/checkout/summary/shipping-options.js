define(
    [
        'Magento_Checkout/js/view/summary/abstract-total',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals',
        'underscore',
        'knockout'
    ],
    function (Component, quote, totals, _, ko) {
        "use strict";

        return Component.extend({
            defaults: {
                template: 'Azra_ShippingOptions/checkout/summary/shipping-options',
                optionsTitle: window.checkoutConfig.shipping_options_title,
                shipping_option_fee_code: window.checkoutConfig.shipping_option_fee_code,
                shippingLabel: ko.observable(null),
                value: ko.observable(0.0),
                shouldDisplay: ko.observable(false)
            },

            initialize: function() {
                this._super();

                quote.totals.subscribe((function (newTotals) {
                    let shipping_option = this.getShippingFee(newTotals);

                    this.shippingLabel(shipping_option.title);
                    this.shouldDisplay(this.checkDisplay(shipping_option));
                    this.value(this.getFormattedTotalValue(shipping_option));

                }).bind(this));
            },

            checkDisplay: function(shippingOption) {
                return _.isObject(shippingOption);
            },

            getShippingOptionLabel: function(){
                return shippingLabel;
            },

            getShippingFee: function(totals){
                let self = this;

                if (typeof totals.total_segments === 'undefined' || !totals.total_segments instanceof Array) {
                    return false;
                }

                let shipping_option_segment = totals.total_segments.filter(function(segment){
                    return segment.code == self.shipping_option_fee_code;
                });

                if (shipping_option_segment instanceof Array && shipping_option_segment.length) {
                    return shipping_option_segment[0];
                }
                return false;
            },

            getFormattedTotalValue: function(shippingOption) {
                return this.getFormattedPrice(shippingOption.value);
            }
        });
    }
);
