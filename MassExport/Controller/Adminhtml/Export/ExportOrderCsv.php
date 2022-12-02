<?php

namespace TUTJunior\MassExport\Controller\Adminhtml\Export;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Model\Order\Invoice;

class ExportOrderCsv extends \Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction
{
    public function __construct
    (
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        OrderManagementInterface $orderManagement,
        \Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory $itemCollection
    )
    {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->fileFactory = $fileFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->orderManagement = $orderManagement;
        $this->itemCollection = $itemCollection;
    }

    protected function massAction(AbstractCollection $collection)
    {
        set_time_limit(5);

        $this->exportCSV($collection->getItems());

        foreach ($collection->getItems() as $order) {
            if (!$order->getEntityId()) {
                continue;
            }
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('TUTJunior_MassExport::exportcsv');
    }

    private function exportCSV(array $orders)
    {
        $fileName = 'csv_order.csv';
        $filePath = $this->directoryList->getPath(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR)
            . "/" . $fileName;

        $data = $this->getExportData($orders);
        $this->csvProcessor
            ->setDelimiter(';')
            ->setEnclosure('"')
            ->saveData(
                $filePath,
                $data
            );

        return $this->fileFactory->create(
            $fileName,
            [
                'type' => "filename",
                'value' => $fileName,
                'rm' => true,
            ],
            \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }

    private function getExportData(array $orders)
    {
        $customerOrder = $this->collectionFactory->create();
        $itemCollection = $this->itemCollection->create();

        $result = [];
        $result[] = [
            'Order Increment Id',
            'Order Status',
            'SKU',
            'Product Name',
            'Qty',
            'Item Status',
            'Tax Amount',
            'Discount Amount',
            'Customer email',
            'Times Purchased Product',
            'Store name',
            'Purchase Date',
            'Bill-to Name',
            'Ship-to Name',
            'Payment method',
            'Line total',
            'Coupon code',
            'Promotion Name',
            'Order comment'
        ];

        foreach($orders as $order)
        {
            $comments = $this->orderManagement->getCommentsList($order->getId())->getItems();
            $comment = reset($comments);
            $comment = $comment ? $comment->getComment() : null;

            $cusOr = clone $customerOrder;
            $cusOr->addAttributeToFilter('customer_email', $order->getCustomerEmail());
            $orderIds = $cusOr->getAllIds();

            foreach($order->getItems() as $item)
            {
                $itColec = clone $itemCollection;
                $itColec->addFieldToFilter('sku',$item->getSku())
                    ->addFieldToFilter('order_id',['in'=> $orderIds]);

                $result[] = [
                    $order->getIncrementId(),
                    $order->getStatus(),
                    $item->getSku(),
                    $item->getName(),
                    $item->getQtyOrdered(),
                    $item->getStatus()->getText(),
                    $item->getTaxAmount(),
                    $item->getDiscountAmount(),
                    $order->getCustomerEmail(),
                    count($itColec),
                    $order->getStoreName(),
                    $order->getCreatedAt(),
                    $order->getBillingAddress()->getData('firstname').' '.$order->getShippingAddress()->getData('lastname'),
                    $order->getShippingAddress()->getData('firstname').' '.$order->getShippingAddress()->getData('lastname'),
                    $order->getPayment()->getAdditionalInformation('method_title'),
                    $item->getRowTotal(),
                    null,
                    null,
                    null
                ];
            }

            $result[] = [
                $order->getIncrementId(),
                $order->getStatus(),
                null,
                null,
                null,
                null,
                $order->getTaxAmount(),
                $order->getDiscountAmount(),
                $order->getCustomerEmail(),
                null,
                $order->getStoreName(),
                $order->getCreatedAt(),
                $order->getBillingAddress()->getData('firstname').' '.$order->getShippingAddress()->getData('lastname'),
                $order->getShippingAddress()->getData('firstname').' '.$order->getShippingAddress()->getData('lastname'),
                $order->getPayment()->getAdditionalInformation('method_title'),
                $order->getGrandTotal(),
                $order->getCouponCode(),
                $order->getCouponRuleName(),
                $comment
            ];
        }

        return $result;
    }
}
