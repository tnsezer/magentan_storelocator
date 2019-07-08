<?php

namespace Magentan\StoreLocator\Block\Map;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magentan\StoreLocator\Api\StoreLocatorRepositoryInterface;

class Search extends Template
{

    /**
     * @var StoreLocatorRepositoryInterface
     */
    protected $_storeLocator;

    /**
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_criteriaFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param StoreLocatorRepositoryInterface $storeLocator
     * @param SearchCriteriaBuilder $criteria
     * @param CountryFactory $countryFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreLocatorRepositoryInterface $storeLocator,
        SearchCriteriaBuilder $criteria,
        CountryFactory $countryFactory,
        array $data = []
    )
    {
        $this->_storeLocator = $storeLocator;
        $this->_countryFactory = $countryFactory;
        $this->_criteriaFactory = $criteria;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getCountries()
    {
        $criteria = $this->_criteriaFactory->addFilter('status', 1, 'eq')->create();

        return $this->_storeLocator->getCountryList($criteria)->getItems();
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function getCountryNameByCode(string $code)
    {
        return $this->_countryFactory->create()->loadByCode($code)->getName();
    }
}