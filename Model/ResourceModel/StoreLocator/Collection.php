<?php

namespace Magentan\StoreLocator\Model\ResourceModel\StoreLocator;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('Magentan\StoreLocator\Model\StoreLocator', 'Magentan\StoreLocator\Model\ResourceModel\StoreLocator');
    }
}