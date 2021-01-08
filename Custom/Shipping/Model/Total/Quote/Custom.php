<?php

namespace Custom\Shipping\Model\Total\Quote;

/**
 * Class Custom
 * @package Meetanshi\HelloWorld\Model\Total\Quote
 */
class Custom extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal {

	/**
	 * @var \Magento\Framework\Pricing\PriceCurrencyInterface
	 */
	protected $_priceCurrency;

	/**
	 * Custom constructor.
	 * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
	 */
	public function __construct(
		\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
	) {
		$this->_priceCurrency = $priceCurrency;
	}

	/**
	 * [getLabel description]
	 * @return [type] [description]
	 */
	public function getLabel() {
		return __('Custom Coupon');
	}

	/**
	 * @param \Magento\Quote\Model\Quote $quote
	 * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
	 * @param \Magento\Quote\Model\Quote\Address\Total $total
	 * @return $this|bool
	 */
	public function collect(
		\Magento\Quote\Model\Quote $quote,
		\Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
		\Magento\Quote\Model\Quote\Address\Total $total
	) {
		parent::collect($quote, $shippingAssignment, $total);

		$discountAmount = $quote->getApplyCustomDiscount();
		if ($discountAmount) {

			$label = $this->getLabel();
			// $discountAmount = $this->_priceCurrency->convert($discountAmount);
			$appliedCartDiscount = 0;
			$discountAmount = -$discountAmount;

			if ($total->getDiscountDescription()) {
				// If a discount exists in cart and another discount is applied, the add both discounts.
				$appliedCartDiscount = $total->getDiscountAmount();
				$discountAmount = $total->getDiscountAmount() + $discountAmount;
				$label = $total->getDiscountDescription() . ', ' . $label;
			}
			$total->setDiscountDescription($label);
			$total->setDiscountAmount($discountAmount);
			$total->setBaseDiscountAmount($discountAmount);
			$total->setSubtotalWithDiscount($total->getSubtotal() + $discountAmount);
			$total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $discountAmount);
			if ($appliedCartDiscount) {
				$discountAmount -= $appliedCartDiscount;
			}
			$total->addTotalAmount($this->getCode(), $discountAmount);
			$total->addBaseTotalAmount($this->getCode(), $discountAmount);
			// $total->setBaseGrandTotal($total->getBaseGrandTotal() - $discountAmount);
			$quote->setCustomDiscount($discountAmount);
		}
		return $this;
	}

	/**
	 * [fetch description]
	 * @param  \Magento\Quote\Model\Quote               $quote
	 * @param  \Magento\Quote\Model\Quote\Address\Total $total
	 * @return [type]
	 */
	public function fetch(
		\Magento\Quote\Model\Quote $quote,
		\Magento\Quote\Model\Quote\Address\Total $total
	) {
		$result = null;
		$amount = $total->getDiscountAmount();
		if ($amount != 0) {
			$description = $total->getDiscountDescription();
			$result = [
				'code' => $this->getCode(),
				'title' => strlen($description) ? __('Discount (%1)', $description) : __('Discount'),
				'value' => $amount,
			];
		}
		return $result;
	}
}