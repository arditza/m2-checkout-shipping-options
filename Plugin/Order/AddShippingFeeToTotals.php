<?php
declare(strict_types = 1);

namespace Azra\ShippingOptions\Plugin\Order;

use Magento\Sales\Model\Order;
use Magento\Framework\DataObject;
use Magento\Sales\Block\Order\Totals;
use Magento\Quote\Api\Data\TotalsInterface;
use Azra\ShippingOptions\Model\Total\Quote\ShippingOption;
use Azra\ShippingOptions\Model\Options\ShippingOptionsProvider;

/**
 *
 */
class AddShippingFeeToTotals
{

	protected $_shippingOptionsProvider;

	/**
	 * initialize class depedencies
	 *
	 * @param ShippingOptionsProvider $configHelper
	 */
	public function __construct(
		ShippingOptionsProvider $shippingOptionsProvider
	)
	{
		$this->_shippingOptionsProvider = $shippingOptionsProvider;
	}


	/**
	 * add shipping options fee to totals rows
	 *
	 * @param  Totals $subject
	 * @param  Order  $order
	 *
	 * @return Order
	 */
	public function afterGetOrder(Totals $subject, Order $order): Order
    {
        if (empty($subject->getTotals())) {
            return $order;
        }

        if ($subject->getTotal(ShippingOption::TOTAL_CODE) !== false) {
            return $order;
        }
        if (!is_null($order->getShippingOptionFee())) {
	        $subject->addTotalBefore(new DataObject([
	            'code' => ShippingOption::TOTAL_CODE,
	            'base_value' => (float) $order->getShippingOptionFee(),
	            'value' => (float) $order->getShippingOptionFee(),
	            'label' => __("Shipping Options - %1",$order->getShippingOptionLabel())
	        ]), "last");
        }

        return $order;
    }
}
