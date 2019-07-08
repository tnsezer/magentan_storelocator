<?php

namespace Magentan\StoreLocator\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magentan\StoreLocator\Model\StoreLocatorFactory;

class Save extends Action
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
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            try{
                $model = $this->storeLocatorFactory->create();
                $id = $this->getRequest()->getParam('id');
                if ($id > 0) {
                    $model = $model->load($id);
                }else{
                    unset($data['id']);
                }

                $data['position'] = json_encode($data['position']);
                $model->setData($data);
                $model->save();

                $this->messageManager->addSuccess(__('Successfully saved the store.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);

                return $this->_redirect('*/*/*/edit', ['id' => $model->getId()]);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);

                return $this->_redirect('*/*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }else {
            return $this->_redirect('*/*/');
        }
    }
}