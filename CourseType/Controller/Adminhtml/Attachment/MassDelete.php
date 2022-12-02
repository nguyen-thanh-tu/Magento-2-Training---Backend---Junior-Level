<?php

namespace TUTJunior\CourseType\Controller\Adminhtml\Attachment;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use TUTJunior\CourseType\Model\Attachment as ModelAttachment;

class MassDelete extends \Magento\Backend\App\Action
{
    public function __construct
    (
        ModelAttachment $modelAttachment,
        Context $context
    )
    {
        $this->modelAttachment = $modelAttachment;
        parent::__construct($context);
    }

    public function execute()
    {
        $ids = $this->getRequest()->getParams();
        $model = $this->modelAttachment->create();
        $model->deleteMultiple($ids);
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT)->setPath('course/attachment/index');
    }
}
