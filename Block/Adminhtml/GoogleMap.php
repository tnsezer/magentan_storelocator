<?php

namespace Magentan\StoreLocator\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Store\Model\ScopeInterface;

class GoogleMap extends Template
{

    protected $_template = 'block/googlemap.phtml';

    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    /**
     * Get Api Key
     *
     * @return string|bool
     */
    public function getApi(){
        return $this->_scopeConfig->getValue('storelocator/geoapi/google_api', ScopeInterface::SCOPE_STORE);
    }
}