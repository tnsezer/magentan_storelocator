<?php

namespace Magentan\StoreLocator\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Url;
use Magento\Store\Model\ScopeInterface;

class Router implements RouterInterface
{

    /**
     * @var bool
     */
    private $dispatched = false;

    /**
     * @var ActionFactory
     */
    protected $actionFactory;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Constructor
     *
     * @param ActionFactory $actionFactory
     * @param EventManagerInterface $eventManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ActionFactory $actionFactory,
        EventManagerInterface $eventManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(RequestInterface $request)
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        if (!$this->dispatched) {
            $identifier = trim($request->getPathInfo(), '/');
            $this->eventManager->dispatch('core_controller_router_match_before', [
                'router' => $this,
                'condition' => new DataObject(['identifier' => $identifier, 'continue' => true])
            ]);

            $route = $this->_scopeConfig->getValue('storelocator/general/url', ScopeInterface::SCOPE_STORE);

            if ($route !== '' && $identifier === $route) {
                $request->setModuleName('storelocator')
                    ->setControllerName('index')
                    ->setActionName('index');
                $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                $this->dispatched = true;

                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward'
                );
            }

            return null;
        }
    }
}