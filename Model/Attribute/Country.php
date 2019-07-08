<?php

namespace Magentan\StoreLocator\Model\Attribute;

use Magento\Directory\Model\CountryFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

class Country extends AbstractSource implements OptionSourceInterface
{

    /**
     * @var Config
     */
    protected $_configCacheType;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Country factory
     *
     * @var CountryFactory
     */
    protected $_countryFactory;

    /**
     * Constructor
     *
     * @param CountryFactory $countryFactory
     * @param StoreManagerInterface $storeManager
     * @param Config $configCacheType
     */
    public function __construct(
        CountryFactory $countryFactory,
        StoreManagerInterface $storeManager,
        Config $configCacheType
    ) {
        $this->_countryFactory = $countryFactory;
        $this->_storeManager = $storeManager;
        $this->_configCacheType = $configCacheType;
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getAllOptions()
    {
        $cacheKey = 'COUNTRYOFMANUFACTURE_SELECT_STORE_' . $this->_storeManager->getStore()->getCode();
        if ($cache = $this->_configCacheType->load($cacheKey)) {
            return unserialize($cache);
        }

        /** @var \Magento\Directory\Model\Country $country */
        $country = $this->_countryFactory->create();

        /** @var \Magento\Directory\Model\ResourceModel\Country\Collection $collection */
        $collection = $country->getResourceCollection();

        $options = $collection->load()->toOptionArray();
        $this->_configCacheType->save(serialize($options), $cacheKey);

        return $options;
    }
}
