<?php
declare(strict_types = 1);
namespace Azra\ShippingOptions\Model\Total\Quote;

use Magento\Framework\Phrase;

/**
 *
 */
class ShippingOption extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    const CURRENCY_CODE_PATH = "currency/options/base";

    const TOTAL_CODE = "shipping_option_fee";
    const BASE_TOTAL_CODE = "base_shipping_option_fee";

    protected $configHelper;
    protected $currencyFactory;
    protected $shippingOptionsProvider;

    protected $baseCurrency = null;

    /**
     * initialize class depedencies
     *
     * @param \Azra\ShippingOptions\Helper\ConfigHelper     $configHelper    [description]
     * @param \Magento\Directory\Model\CurrencyFactory $currencyFactory [description]
     */
	public function __construct(
        \Azra\ShippingOptions\Helper\ConfigHelper $configHelper,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Azra\ShippingOptions\Model\Options\ShippingOptionsProvider $shippingOptionsProvider
	) {
		$this->configHelper = $configHelper;
        $this->currencyFactory = $currencyFactory;
        $this->shippingOptionsProvider = $shippingOptionsProvider;
	}

    /**
     * get label of this total
     *
     * @return Phrase
     */
    public function getLabel(): Phrase
    {
        return __("Shipping Option Fee");
    }

    /**
     * collect totals
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this|bool
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ){
        parent::collect($quote, $shippingAssignment, $total);

        if (count($shippingAssignment->getItems()) == 0 || !$this->configHelper->isEnabled()) {
            return $this;
        }

        $shippingOption = $this->shippingOptionsProvider->getShippingOption($quote);

        if ($shippingOption === false) {
            return $this;
        }
        $shippingOption["price"] = (float) $shippingOption["price"];
        $currency = $quote->getStore()->getCurrentCurrency();
        $extraFee = $this->getBaseCurrency()->convert($shippingOption["price"], $currency);

        $total->setData(static::TOTAL_CODE, $extraFee);
        $total->setData(static::BASE_TOTAL_CODE, $shippingOption["price"]);

        $total->setTotalAmount(static::TOTAL_CODE, $extraFee);
        $total->setBaseTotalAmount(static::TOTAL_CODE, $shippingOption["price"]);

        return $this;
    }


    /**
     *
     * @param  \Magento\Quote\Model\Quote               $quote
     * @param  \Magento\Quote\Model\Quote\Address\Total $total
     *
     * @return mixed
     */
    public function fetch(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Address\Total $total
    ){
        $result = null;
        $shippingOption = $this->shippingOptionsProvider->getShippingOption($quote);
        if ($shippingOption !== false) {
            $currency = $quote->getStore()->getCurrentCurrency();
            $shippingOption["price"] = (float) $shippingOption["price"];
            $value = $this->getBaseCurrency()->convert($shippingOption["price"], $currency);
            $title = $this->prepareTitle($shippingOption, $currency);
            $result = [
                'code' => self::TOTAL_CODE,
                'title' => __($title),
                'base_value' => $shippingOption["price"],
                'value' => $value
            ];
        }
        return $result;
    }

    protected function prepareTitle($shippingOption, $currency)
    {
        $phrase  = __('Shipping');
        $price = $currency->format($shippingOption['price'], [], false, false);
        $title = str_replace($price, '', $shippingOption['title']);

        return $phrase . ' - '. $title;
    }

    /**
     * get store currency
     *
     * @return \Magento\Directory\Model\Currency
     */
    public function getBaseCurrency()
    {
        if (is_null($this->baseCurrency)) {
            $currencyCode = $this->configHelper->getConfig(self::CURRENCY_CODE_PATH);
            $this->baseCurrency = $this->currencyFactory->create()->load($currencyCode);
        }
        return $this->baseCurrency;
    }
}