<?php

namespace Magentan\StoreLocator\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magentan\StoreLocator\Api\Data\StoreLocatorInterface;

interface StoreLocatorRepositoryInterface
{

    /**
     * Save StoreLocator
     *
     * @param StoreLocatorInterface $storeLocator
     *
     * @return StoreLocatorInterface
     *
     * @throws LocalizedException
     */
    public function save(
        StoreLocatorInterface $storeLocator
    );

    /**
     * Retrieve StoreLocator
     *
     * @param string $storelocatorId
     *
     * @return StoreLocatorInterface
     *
     * @throws LocalizedException
     */
    public function getById($storelocatorId);

    /**
     * Retrieve StoreLocator matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return StoreLocatorInterface
     *
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete StoreLocator
     *
     * @param StoreLocatorInterface $storeLocator
     *
     * @return bool true on success
     *
     * @throws LocalizedException
     */
    public function delete(
        StoreLocatorInterface $storeLocator
    );

    /**
     * Delete StoreLocator by ID
     *
     * @param string $storelocatorId
     *
     * @return bool true on success
     *
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($storelocatorId);
}