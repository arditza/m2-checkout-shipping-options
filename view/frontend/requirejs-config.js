var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
                'Azra_ShippingOptions/js/model/shipping-save-processor/payload-extender-mixin': true
            }
        }
    },
    map: {
    	"*" : {
    		"Magento_Checkout/js/model/shipping-service": "Azra_ShippingOptions/js/model/shipping-service"
    	}
    }
};
