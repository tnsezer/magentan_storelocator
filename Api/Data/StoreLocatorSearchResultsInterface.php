<?php

namespace Magentan\StoreLocator\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use Magentan\StoreLocator\Api\Data\StoreLocatorInterface;

interface StoreLocatorSearchResultsInterface extends SearchResultsInterface
{

    /**
     * Get StoreLocator list.
     *
     * @return StoreLocatorInterface[]
     */
    public function getItems();

    /**
     * Set id list.
     *
     * @param StoreLocatorInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);
}