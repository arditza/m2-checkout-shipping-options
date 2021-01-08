<?php

namespace Custom\Shipping\Model\Sales\Order\Invoice;

use Custom\Shipping\Model\Total\Quote\ShippingOption as ShippingOptionTotals;

/**
 *
 */
class ShippingOption extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{

	 /**
     * @inheritdoc
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        parent::collect($invoice);

        $order = $invoice->getOrder();
        if (is_null($order->getShippingOptionFee())) {
        	return $this;
        }

        $shippingOptionFee = $order->getShippingOptionFee();
        $invoice->setData(ShippingOptionTotals::TOTAL_CODE, $shippingOptionFee);

        if (round($shippingOptionFee, 2) != 0)
        {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $shippingOptionFee);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $shippingOptionFee);
        }
        return $this;
    }
}