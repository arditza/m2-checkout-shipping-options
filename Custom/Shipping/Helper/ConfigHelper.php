<?php

namespace Custom\Shipping\Helper;


/**
 *
 */
class ConfigHelper extends \Magento\Framework\App\Helper\AbstractHelper
{

	const IS_ENABLED = "shipping_options/general/active";
	const SHIPPING_OPTIONS = "shipping_options/general/shipping_options";
    const SHIPPING_OPTIONS_TITLE = "shipping_options/general/shipping_options_title";
    const IGNORE_SHIPPING_METHODS = "shipping_options/general/ignore_method";

    public function __construct(
    	\Magento\Framework\App\Helper\Context $context,
    	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
    	$this->_scopeConfig = $scopeConfig;

    	parent::__construct($context);
    }

    public function getConfig($config_path,$storeId = null)
    {
    	return $this->_scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * check if extra shipping fees are applied to all hsipping methods
     *
     * @return boolean
     */
    public function isEnabled()
    {
    	return $this->getConfig(self::IS_ENABLED);
    }

    /**
     * get all extra shipping fees specified in backend
     *
     * @return array
     */
    public function getShippingOptions()
    {
    	return $this->getConfig(self::SHIPPING_OPTIONS);
    }

    /**
     * get shipping options title
     *
     * @return string
     */
    public function getShippingOptionsTitle($storeId = null)
    {
        return $this->getConfig(self::SHIPPING_OPTIONS_TITLE,$storeId);
    }

    /**
     * get ignored hsipping methods
     *
     * @return string
     */
    public function getIgnoredShippingMethods($storeId = null)
    {
        return $this->getConfig(self::IGNORE_SHIPPING_METHODS,$storeId);
    }
}