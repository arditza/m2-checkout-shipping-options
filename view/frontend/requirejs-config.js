var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Custom_Shipping/js/model/shipping-save-processor/payload-extender-mixin': true
            }
        }
    },
    map: {
    	"*" : {
    		"Magento_Checkout/js/model/shipping-service": "Custom_Shipping/js/model/shipping-service"
    	}
    }
};