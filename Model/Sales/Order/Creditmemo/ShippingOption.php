<?php

namespace Azra\ShippingOptions\Model\Sales\Order\Creditmemo;

use Azra\ShippingOptions\Model\Total\Quote\ShippingOption as ShippingOptionTotals;
/**
 *
 */
class ShippingOption extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{

	/**
     * @inheritdoc
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        parent::collect($creditmemo);
        $order = $creditmemo->getOrder();
        if (is_null($order->getShippingOptionFee())) {
        	return $this;
        }

        $shippingOptionFee = $order->getShippingOptionFee();
        $creditmemo->setData(ShippingOptionTotals::TOTAL_CODE, $shippingOptionFee);

        if (round($shippingOptionFee, 2) != 0)
        {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $shippingOptionFee);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $shippingOptionFee);
        }
        return $this;
    }
}