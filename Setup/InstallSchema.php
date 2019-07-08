<?php

namespace Magentan\StoreLocator\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $table = $setup->getConnection()->newTable($setup->getTable('store_locator'))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'identity' => true, 'primary' => true]
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255
            )
            ->addColumn(
                'status',
                Table::TYPE_BOOLEAN,
                null,
                ['default' => false]
            )
            ->addColumn(
                'address',
                Table::TYPE_TEXT,
                null
            )
            ->addColumn(
                'city',
                Table::TYPE_TEXT,
                255
            )
            ->addColumn(
                'country',
                Table::TYPE_TEXT,
                255
            )
            ->addColumn(
                'zip',
                Table::TYPE_TEXT,
                100
            )
            ->addColumn(
                'position',
                Table::TYPE_TEXT,
                255
            )
            ->addColumn(
                'website',
                Table::TYPE_TEXT,
                100
            )
            ->addColumn(
                'phone',
                Table::TYPE_TEXT,
                15
            )
            ->addColumn(
                'email',
                Table::TYPE_TEXT,
                100
            );

        $setup->getConnection()->createTable($table);
    }
}