<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Azra\ShippingOptions\Model\Sales\Order\Invoice;

class Discount extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal {

	/**
	 * [getLabel description]
	 * @return [type] [description]
	 */
	public function getLabel() {
		return __('Custom Coupon');
	}

	/**
	 * @param \Magento\Sales\Model\Order\Invoice $invoice
	 * @return $this
	 */
	public function collect(\Magento\Sales\Model\Order\Invoice $invoice) {
		$totalDiscountAmount = $invoice->getDiscountAmount();
		$baseTotalDiscountAmount = $invoice->getBaseDiscountAmount();
		$order = $invoice->getOrder();
		$discountAmount = $order->getApplyCustomDiscount();
		if ($discountAmount) {
			$totalDiscountAmount -= $discountAmount;
			$baseTotalDiscountAmount -= $discountAmount;
		}
		$invoice->setDiscountAmount($totalDiscountAmount);
		$invoice->setBaseDiscountAmount($baseTotalDiscountAmount);
		$invoice->setDiscountDescription($this->getLabel());

		$invoice->setGrandTotal($invoice->getGrandTotal() + $totalDiscountAmount);
		$invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseTotalDiscountAmount);

		return $this;
	}
}