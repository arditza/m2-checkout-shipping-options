define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalAction, payload) {
            payload = originalAction(payload);

            // Add your values to the payload here
            payload.addressInformation['extension_attributes'] = { "shipping_option_code" : $("[name='shipping_options']").val()};

            return payload;
        });
    };
});