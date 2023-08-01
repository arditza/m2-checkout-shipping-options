<?php

namespace Azra\ShippingOptions\Model\Payment;

class Custom extends \Magento\Payment\Model\Method\AbstractMethod
{

    protected $_code = "custom";

    protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
