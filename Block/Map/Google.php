<?php

namespace Magentan\StoreLocator\Block\Map;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Locale\Resolver;
use Magento\Store\Model\ScopeInterface;
use Magentan\StoreLocator\Api\StoreLocatorRepositoryInterface;

class Google extends Template
{

    /**
     * @var StoreLocatorRepositoryInterface
     */
    protected $_storeLocator;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $_criteriaFactory;

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * @var Resolver
     */
    protected $_store;

    /**
     * Constructor
     *
     * @param Context $context
     * @param StoreLocatorRepositoryInterface $storeLocator
     * @param SearchCriteriaBuilder $criteria
     * @param CountryFactory $countryFactory
     * @param Resolver $store
     * @param JsonHelper $jsonHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        StoreLocatorRepositoryInterface $storeLocator,
        SearchCriteriaBuilder $criteria,
        CountryFactory $countryFactory,
        Resolver $store,
        JsonHelper $jsonHelper,
        array $data = []
    ) {
        $this->_storeLocator = $storeLocator;
        $this->_criteriaFactory = $criteria;
        $this->_countryFactory = $countryFactory;
        $this->_store = $store;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getApi()
    {
        return $this->_scopeConfig->getValue('storelocator/geoapi/google_api', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->_scopeConfig->getValue('storelocator/geoapi', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get stores for Map
     *
     * @param bool $json
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getStores($json = false)
    {

        $criteria = $this->_criteriaFactory->addFilter('status', 1, 'eq')->create();

        $items = $this->_storeLocator->getList($criteria)->getItems();

        if ($json) {
            $result = [];
            foreach ($items as $collection) {
                $result[] = $collection->toArray();
            }
            $items = $this->jsonHelper->jsonEncode($result);
        }

        return $items;
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

    /**
     * @return mixed
     */
    public function getLocaleCode()
    {
        return explode('_', $this->_store->getLocale())[0];
    }
}