<?php

namespace TUTJunior\CourseType\Controller\Document;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use TUTJunior\CourseType\Model\ResourceModel\Attachment\CollectionFactory as AttachmentCollection;

class AjaxDocProdId extends \Magento\Framework\App\Action\Action
{
    private $resultJsonFactory;
    private $attachmentCollection;

    protected $layout;

    public function __construct
    (
        \Magento\Catalog\Model\ProductRepository $productRepository,
        AttachmentCollection $attachmentCollection,
        JsonFactory $resultJsonFactory,
        Context $context
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->attachmentCollection = $attachmentCollection;
    }

    public function execute()
    {
        $product = $this->productRepository->getById((int)$this->getRequest()->getParam('product_id'));
        $docName = $product->getDocument();
        $resultJson = $this->resultJsonFactory->create();
        $result = [];

        $attachmentCollection = $this->attachmentCollection->create();
        $doc = $attachmentCollection
            ->addFieldToFilter('file_name', $docName)
            ->getLastItem();

        $result['icon'] = $doc->getIcon();
        $result['file_label'] = $doc->getFileLabel();

        return $resultJson->setData($result);
    }
}
