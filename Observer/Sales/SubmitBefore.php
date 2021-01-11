<?php

namespace Azra\ShippingOptions\Observer\Sales;

/**
 *
 */
class SubmitBefore implements \Magento\Framework\Event\ObserverInterface
{

	public function __construct(
		\Azra\ShippingOptions\Helper\ConfigHelper $configHelper,
		\Azra\ShippingOptions\Model\Options\ShippingOptionsProvider $shippingOptionsProvider
	)
	{
		$this->_configHelper = $configHelper;
		$this->_shippingOptionsProvider = $shippingOptionsProvider;
	}


	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if (!$this->_configHelper->isEnabled()) {
			return $this;
		}
		$order = $observer->getEvent()->getOrder();
		$quote = $observer->getEvent()->getQuote();
		$shippingOption = $this->_shippingOptionsProvider->getShippingOption($quote);
		if ($shippingOption) {
			$order->setShippingOptionFee($shippingOption["price"]);
			$order->setShippingOptionLabel($shippingOption["title"]);
		}
		return $this;
	}
}