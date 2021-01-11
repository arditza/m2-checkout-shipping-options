define(
	[
		'jquery',
	 	'Magento_Checkout/js/model/quote',
	    'Magento_Checkout/js/model/shipping-rate-processor/new-address',
	    'Magento_Checkout/js/model/shipping-rate-processor/customer-address',
	    'Magento_Checkout/js/model/shipping-rate-registry',
	 	'Magento_Checkout/js/action/get-totals',
 	],
 	function (
 		$,
 		quote,
 		defaultProcessor,
 		customerAddressProcessor,
 		rateRegistry,
 		getTotalsAction
 	) {
		'use strict';

		return {
			reloadShippingRates: function(){
				var processors = [];
				processors.default =  defaultProcessor;
				processors['customer-address'] = customerAddressProcessor;

				rateRegistry.set(quote.shippingAddress().getCacheKey(), null);

				var type = quote.shippingAddress().getType();
				if (processors[type]) {
					processors[type].getRates(quote.shippingAddress());
				} else {
					processors.default.getRates(quote.shippingAddress());
				}
			},
			reloadTotals: function(){
				var deferred = $.Deferred();
				getTotalsAction([], deferred);
			}
		}
 });
