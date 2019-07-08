<?php

namespace Magentan\StoreLocator\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magentan\StoreLocator\Api\Data\StoreLocatorInterface;

/**
 * Class StoreLocator
 */
class StoreLocator extends AbstractModel implements StoreLocatorInterface
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'store_locator';

    /**
     * @var JsonHelper
     */
    private $jsonHelper;

    /**
     * Constructor
     *
     * @param JsonHelper $jsonHelper
     * @param Context $context
     * @param Registry $registry
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        JsonHelper $jsonHelper,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magentan\StoreLocator\Model\ResourceModel\StoreLocator');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->getData('status');
    }

    /**
     * @inheritdoc
     */
    public function getAddress()
    {
        return $this->getData('address');
    }

    /**
     * @inheritdoc
     */
    public function getPhone()
    {
        return $this->getData('phone');
    }

    /**
     * @inheritdoc
     */
    public function getCity()
    {
        return $this->getData('city');
    }

    /**
     * @inheritdoc
     */
    public function getCountry()
    {
        return $this->getData('country');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * @inheritdoc
     */
    public function getWebsite()
    {
        return $this->getData('website');
    }

    /**
     * @inheritdoc
     */
    public function getPosition()
    {
        return $this->jsonHelper->jsonDecode($this->getData('position'));
    }
}