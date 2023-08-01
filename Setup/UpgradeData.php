<?php

namespace Azra\ShippingOptions\Setup;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $installer = $setup;
            $installer->startSetup();
            $installer->getConnection()
                ->addColumn(
                    $installer->getTable('quote'),
                    'shipping_option_code',
                    [
                        'type'    => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length'  => 25,
                        'comment' => 'Shipping Option Extra Fee Code',
                    ]
                );
            $installer->endSetup();
        }
    }
}
