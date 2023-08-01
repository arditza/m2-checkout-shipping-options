<?php

namespace Azra\ShippingOptions\Block\System\Config\Form\Field;

use \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 *
 */
class ShippingOptions extends AbstractFieldArray
{

    /**
     * Initialise form fields
     *
     * @return void
     */
    protected function _construct()
    {

        $this->addColumn('title', array(
            'label' => __('Title'),
            'class' => 'title',
        ));

        $this->addColumn('code', array(
            'label' => __('Code'),
            'class' => 'code',
        ));

        $this->addColumn('price', array(
            'label' => __('Price'),
            'class' => "price"
        ));

        $this->_addButtonLabel = __('Add Shipping Fee');

        parent::_construct();
    }


    /**
     * add column to shipping options configuration table
     * @param string $name
     * @param array $params
     */
    public function addColumn($name, $params)
    {
        $this->_columns[$name] = array(
            'label'     => $params["label"],
            'size'      => empty($params['size']) ? false : $params['size'],
            'style'     => empty($params['style']) ? null : $params['style'],
            'class'     => empty($params['class']) ? null : $params['class'],
            'renderer'  => false,
        );
        if ((!empty($params['renderer'])) && ($params['renderer'] instanceof \Magento\Framework\View\Element\AbstractBlock)) {
            $this->_columns[$name]['renderer'] = $params['renderer'];
        }
    }
}
