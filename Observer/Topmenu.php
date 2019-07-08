<?php

namespace Magentan\StoreLocator\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Event\ObserverInterface;

class Topmenu implements ObserverInterface
{

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Topmenu constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @inheritdoc
     */
    public function execute(EventObserver $observer)
    {
        $config = $this->_scopeConfig->getValue('storelocator/general', ScopeInterface::SCOPE_STORE);

        if($config['status'] == '1') {
            $menu = $observer->getMenu();
            $tree = $menu->getTree();
            $data = [
                'name' => $config['title'],
                'id' => 'storelocator-menu',
                'url' => '/' . $config['url'],
                'is_active' => 0
            ];
            $node = new Node($data, 'id', $tree, $menu);
            $menu->addChild($node);
        }

        return $this;
    }
}