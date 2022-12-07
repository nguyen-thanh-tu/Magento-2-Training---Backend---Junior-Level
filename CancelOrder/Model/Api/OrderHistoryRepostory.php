<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use TUTJunior\CancelOrder\Api\OrderHistoryRepostoryInterface;
use TUTJunior\CancelOrder\Api\RequestOrderInterfaceFactory;
use TUTJunior\CancelOrder\Api\ResponseOrderInterfaceFactory;
use TUTJunior\CancelOrder\Api\ResponseOrderInterface;

class OrderHistoryRepostory implements OrderHistoryRepostoryInterface
{

    public function __construct
    (
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        OrderRepositoryInterface $orderRepository,
        RequestOrderInterfaceFactory $requestOrderFactory,
        ResponseOrderInterfaceFactory $responseOrderFactory,
        \TUTJunior\CancelOrder\Api\DataArrayInterfaceFactory $dataArray,
        \TUTJunior\CancelOrder\Api\Data\OrderDetailReponseInterfaceFactory $orderDetailReponse,
        \TUTJunior\CancelOrder\Api\Data\ItemArrayReponseInterfaceFactory $itemArrayReponse
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->requestOrderFactory = $requestOrderFactory;
        $this->responseOrderFactory = $responseOrderFactory;
        $this->dataArray = $dataArray;
        $this->orderDetailReponse = $orderDetailReponse;
        $this->itemArrayReponse = $itemArrayReponse;
    }

    /**
     * @inheritDoc
     *
     * @param int $id
     * @return ResponseOrderInterface
     * @throws NoSuchEntityException
     */
    public function getOrderHistoryByCustomerId(int $customerId)
    {
        $orders = $this->_orderCollectionFactory->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'customer_id',
            $customerId
        );

        /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $orders */

        /** @var ResponseOrderInterface $responseOrder */
        $data = [];
        foreach ($orders as $order)
        {
            $responseOrder = [];
            foreach ($order->getData() as $key => $value)
            {
                $dataArray = $this->dataArray->create()
                    ->setName($key)
                    ->setValue($value);
                $responseOrder[] = $dataArray;
            }
            $data[] = $this->itemArrayReponse->create()->setItemArray($responseOrder);
        }
        $responseOrder = $this->responseOrderFactory->create();

        $responseOrder->setOrderHistory($data);

        return $responseOrder;
    }

    /**
     * @inheritDoc
     *
     * @param int $customerId
     * @param int $id
     * @return string
     * @throws NoSuchEntityException
     */
    public function cancelOrder(int $customerId, int $id)
    {
        try{
            $order = $this->orderRepository->get($id);
            if($order->getStatus() != 'canceled' && $order->getCustomerId() == $customerId)
            {
                $order->cancel();
                $order->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
                $order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
                $order->save();
                $return = 'cenceled order id';
            }else{
                $return = 'order id been cenceled before';
            }
        } catch (\Exception $exception) {
            $return =  'Something were wrong 500';
        }

        return $return;
    }

    /**
     * Return a filtered product.
     *
     * @param int $customerId
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\Data\OrderDetailReponseInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function orderDetail(int $customerId, int $id)
    {
        $order = $this->_orderCollectionFactory->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'entity_id',
            $id
        );

        $responseOrder = [];
        foreach ($order->getFirstItem()->getData() as $key => $value)
        {
            $dataArray = $this->dataArray->create();
            $dataArray->setName($key);
            $dataArray->setValue($value);
            $responseOrder[] = $dataArray;
        }

        $orderDetailReponse = $this->orderDetailReponse->create();
        $orderDetailReponse->setOrderDetai($responseOrder);
        return $orderDetailReponse;
    }
}
