<?php

namespace Azra\ShippingOptions\Model\Options;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 *
 */
class ShippingOptionsProvider implements ConfigProviderInterface
{

    /**
     *
     * @var Azra\ShippingOptions\Helper\ConfigHelper
     */
    protected $_configHelper;

    /**
     *
     * @var Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * store id
     *
     * @var null|int
     */
    protected $_storeId = null;


    /**
     * initialize class depedencies
     *
     * @param \Azra\ShippingOptions\Helper\ConfigHelper       $configHelper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
		\Azra\ShippingOptions\Helper\ConfigHelper $configHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
	)
	{
		$this->_configHelper = $configHelper;
        $this->_storeManager = $storeManager;
	}

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(){
		return [
            'shipping_options' => $this->getShippingOptions(),
            'shipping_options_title' => $this->getShippingOptionsTitle(),
            'shipping_option_fee_code' => \Azra\ShippingOptions\Model\Total\Quote\ShippingOption::TOTAL_CODE,
            'ignored_shipping_methods' => $this->_configHelper->getIgnoredShippingMethods($this->getStoreId()) ?? []
        ];
    }

    /**
     * get current store id
     *
     * @return int
     */
    protected function getStoreId()
    {
        if (is_null($this->_storeId)) {
            $this->_storeId = $this->_storeManager->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     * Returns extra shipping opotions saved in th admin panel
     *
     * @return array
     */
    public function getShippingOptions()
    {
        $currency = $this->_storeManager->getStore()->getBaseCurrency();
        $shippingOptions = json_decode($this->_configHelper->getShippingOptions(),true);
        if (is_array($shippingOptions)) {
            $shippingOptions = array_values($shippingOptions);
            foreach ($shippingOptions as $key => $option) {
                $shippingOptions[$key]['title'] .= ' ' . $currency->format($option['price'], [], false, false);
            }
            return $shippingOptions;
        }
        return $shippingOptions;
    }



    /**
     * get shipping option data the quotes extension attributess
     *
     * @param  Quote $quote
     *
     * @return boolean|array
     */
    public function getShippingOption(\Magento\Quote\Model\Quote $quote)
    {
        if (is_null($quote->getShippingOptionCode())) {
            return false;
        }

        $shippingOption = $this->filterShippingOptionByCode($quote->getShippingOptionCode());
        if (is_null($shippingOption) || !isset($shippingOption["price"]) || !is_numeric($shippingOption["price"])) {
            return false;
        }

        return $shippingOption;
    }


    /**
     * fetches shipping option by its code
     *
     * @param  string $shippingOptionCod
     *
     * @return array|null
     */
    public function filterShippingOptionByCode(string $shippingOptionCode) : array
    {
        $shippingOptions = $this->getShippingOptions() ?: [];

        foreach ($shippingOptions as $shippingOption) {
            if (isset($shippingOption["code"]) && $shippingOption["code"] == $shippingOptionCode) {
                return $shippingOption;
            }
        }

        return null;
    }


    /**
     * get shipping options fee title from the configurations
     *
     * @return string
     */
    public function getShippingOptionsTitle()
    {
        return $this->_configHelper->getShippingOptionsTitle($this->getStoreId());
    }
}
