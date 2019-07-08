<?php

namespace Magentan\StoreLocator\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magentan\StoreLocator\Api\Data\StoreLocatorInterface;
use Magentan\StoreLocator\Api\Data\StoreLocatorInterfaceFactory;
use Magentan\StoreLocator\Api\Data\StoreLocatorSearchResultsInterfaceFactory;
use Magentan\StoreLocator\Api\StoreLocatorRepositoryInterface;
use Magentan\StoreLocator\Model\ResourceModel\StoreLocator as ResourceStoreLocator;
use Magentan\StoreLocator\Model\ResourceModel\StoreLocator\CollectionFactory as StoreLocatorCollectionFactory;

class StoreLocatorRepository implements StoreLocatorRepositoryInterface
{

    /**
     * @var ResourceStoreLocator
     */
    protected $resource;

    /**
     * @var StoreLocatorFactory
     */
    protected $storeLocatorFactory;

    /**
     * @var StoreLocatorCollectionFactory
     */
    protected $storeLocatorCollectionFactory;

    /**
     * @var StoreLocatorSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var StoreLocatorInterfaceFactory
     */
    protected $dataStoreLocatorFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;


    /**
     * Constructor
     *
     * @param ResourceStoreLocator $resource
     * @param StoreLocatorFactory $storeLocatorFactory
     * @param StoreLocatorInterfaceFactory $dataStoreLocatorFactory
     * @param StoreLocatorCollectionFactory $storeLocatorCollectionFactory
     * @param StoreLocatorSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceStoreLocator $resource,
        StoreLocatorFactory $storeLocatorFactory,
        StoreLocatorInterfaceFactory $dataStoreLocatorFactory,
        StoreLocatorCollectionFactory $storeLocatorCollectionFactory,
        StoreLocatorSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->storeLocatorFactory = $storeLocatorFactory;
        $this->storeLocatorCollectionFactory = $storeLocatorCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataStoreLocatorFactory = $dataStoreLocatorFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        StoreLocatorInterface $storeLocator
    ) {
        try {
            $this->resource->save($storeLocator);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the storeLocator: %1',
                $exception->getMessage()
            ));
        }

        return $storeLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($storeLocatorId)
    {
        $storeLocator = $this->storeLocatorFactory->create();
        $this->resource->load($storeLocator, $storeLocatorId);
        if (!$storeLocator->getId()) {
            throw new NoSuchEntityException(__('StoreLocator with id "%1" does not exist.', $storeLocatorId));
        }
        return $storeLocator;
    }

    /**
     * {@inheritdoc}
     */
    protected function _setCriteria(
        $collection,
        SearchCriteriaInterface $criteria
    ){
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $fields[] = $filter->getField();
                $condition = $filter->getConditionType() ?: 'eq';
                $conditions[] = [$condition => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }

        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryList(
        SearchCriteriaInterface $criteria
    ){
        $collection = $this->storeLocatorCollectionFactory->create();
        $collection->addFieldToSelect('country')->distinct('country');
        return $this->_setCriteria($collection, $criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->storeLocatorCollectionFactory->create();

        return $this->_setCriteria($collection, $criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        StoreLocatorInterface $storeLocator
    ) {
        try {
            $this->resource->delete($storeLocator);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the StoreLocator: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($storeLocatorId)
    {
        return $this->delete($this->getById($storeLocatorId));
    }
}