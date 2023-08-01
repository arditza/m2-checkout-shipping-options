<?php

namespace Azra\ShippingOptions\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

use Magento\Framework\DB\Ddl\Table;
use Azra\ShippingOptions\Model\Total\Quote\ShippingOption;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), "1.0.2", "<")) {

            $tables = ['sales_order','sales_invoice', 'sales_creditmemo'];

            foreach ($tables as $table) {
                $setup->getConnection()->addColumn($setup->getTable($table), ShippingOption::TOTAL_CODE, [
                    'type' => Table::TYPE_DECIMAL,
                    'length' => '12,4',
                    'nullable' => true,
                    'default' => null,
                    'comment' => "Shipping Option Fee"
                ]);
                $setup->getConnection()->addColumn($setup->getTable($table), "shipping_option_label", [
                    'type' => Table::TYPE_TEXT,
                    'length' => '64',
                    'nullable' => true,
                    'default' => null,
                    'comment' => "Shipping Option Label"
                ]);
            }
        }

        $installer->endSetup();
    }
}
