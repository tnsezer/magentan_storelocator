<?php

namespace Magentan\StoreLocator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magentan\StoreLocator\Model\StoreLocatorFactory;

class Edit extends Action
{

    /**
     * @var StoreLocatorFactory
     */
    protected $storeLocatorFactory;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param PageFactory $resultPageFactory
     * @param StoreLocatorFactory $storeLocatorFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        StoreLocatorFactory $storeLocatorFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->storeLocatorFactory = $storeLocatorFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->storeLocatorFactory->create();

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This store no longer exists.'));

                return $this->_redirect('*/*/');
            }
        }

        $this->_coreRegistry->register('storelocator', $model);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getName() : __('New Store'));

        return $resultPage;
    }
}