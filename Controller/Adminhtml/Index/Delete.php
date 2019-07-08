<?php

namespace Magentan\StoreLocator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magentan\StoreLocator\Model\StoreLocatorFactory;

class Delete extends Action
{

    /**
     * @var StoreLocatorFactory
     */
    protected $storeLocatorFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param StoreLocatorFactory $storeLocatorFactory
     */
    public function __construct(
        Context $context,
        StoreLocatorFactory $storeLocatorFactory
    ) {
        parent::__construct($context);
        $this->storeLocatorFactory = $storeLocatorFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->storeLocatorFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the record.'));
                // go to grid
                return $this->_redirect('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $this->_redirect('*/*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a store to delete.'));

        return $this->_redirect('*/*/');
    }
}