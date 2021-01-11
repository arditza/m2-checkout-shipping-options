<?php 

namespace Azra\ShippingOptions\Helper;

/**
 * 
 */
class Data extends \Magento\Payment\Helper\Data
{
	
    /**
     * Retrieve all payment methods list as an array
     *
     * Possible output:
     * 1) assoc array as <code> => <title>
     * 2) array of array('label' => <title>, 'value' => <code>)
     * 3) array of array(
     *                 array('value' => <code>, 'label' => <title>),
     *                 array('value' => array(
     *                     'value' => array(array(<code1> => <title1>, <code2> =>...),
     *                     'label' => <group name>
     *                 )),
     *                 array('value' => <code>, 'label' => <title>),
     *                 ...
     *             )
     *
     * @param bool $sorted
     * @param bool $asLabelValue
     * @param bool $withGroups
     * @param Store|null $store
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getPaymentMethodList($sorted = true, $asLabelValue = false, $withGroups = false, $store = null)
    {
        $methods = [];
        $groups = [];
        $groupRelations = [];

        foreach ($this->getPaymentMethods() as $code => $data) {
            $storeId = $store ? (int)$store->getId() : null;
            $storedTitle = $this->getMethodStoreTitle($code, $storeId);
            if (!empty($storedTitle)) {
                $methods[$code] = $storedTitle;
            }
            if ($asLabelValue && $withGroups && isset($data['group'])) {
                $groupRelations[$code] = $data['group'];
            }
        }
        if ($asLabelValue && $withGroups) {
            $groups = $this->_paymentConfig->getGroups();
            foreach ($groups as $code => $title) {
                $methods[$code] = $title;
            }
        }
        if ($sorted) {
            asort($methods);
        }
        if ($asLabelValue) {
            $labelValues = [];
            foreach ($methods as $code => $title) {
                $labelValues[$code] = [];
            }
            foreach ($methods as $code => $title) {
                if (isset($groups[$code])) {
                    $labelValues[$code]['label'] = $title;
                    if (!isset($labelValues[$code]['value'])) {
                        $labelValues[$code]['value'] = null;
                    }
                } elseif (isset($groupRelations[$code])) {
                    unset($labelValues[$code]);
                    $labelValues[$groupRelations[$code]]['value'][$code] = ['value' => $code, 'label' => $title];
                } else {
                    $labelValues[$code] = ['value' => $code, 'label' => $title];
                }
            }
            return $labelValues;
        }

        return $methods;
    }

	/**
     * Get config title of payment method
     *
     * @param string $code
     * @param int|null $storeId
     * @return string
     */
    private function getMethodStoreTitle(string $code, ?int $storeId = null): string
    {
        $configPath = sprintf('%s/%s/title', self::XML_PATH_PAYMENT_METHODS, $code);
        return (string) $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
} 