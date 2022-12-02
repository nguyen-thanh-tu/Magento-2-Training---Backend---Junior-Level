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
        ResponseOrderInterfaceFactory $responseOrderFactory
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderRepository = $orderRepository;
        $this->requestOrderFactory = $requestOrderFactory;
        $this->responseOrderFactory = $responseOrderFactory;
    }

    /**
     * @inheritDoc
     *
     * @param int $id
     * @return ResponseOrderInterface
     * @throws NoSuchEntityException
     */
    public function getOrderHistoryByCustomerId(int $id) : mixed
    {
        $orders = $this->_orderCollectionFactory->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'customer_id',
            $id
        );

        /** @var \Magento\Sales\Model\ResourceModel\Order\Collection $orders */

        return $this->getResponseOrderFromProduct($orders);
    }

    /**
     * @param \Magento\Sales\Model\ResourceModel\Order\Collection $order
     * @return ResponseOrderInterface
     */
    private function getResponseOrderFromProduct(\Magento\Sales\Model\ResourceModel\Order\Collection $orders) : mixed
    {
        /** @var ResponseOrderInterface $responseOrder */
        $data = [];
        foreach ($orders as $order)
        {
            $data[] = json_encode($order->getData());
        }
        $responseOrder = $this->responseOrderFactory->create();

        $responseOrder->setOrderHistory($data);

        return $responseOrder;
    }

    /**
     * @inheritDoc
     *
     * @param int $id
     * @return ResponseOrderInterface
     * @throws NoSuchEntityException
     */
    public function cancelOrder($id) : mixed
    {
        $responseOrder = $this->responseOrderFactory->create();
        try{
            $order = $this->orderRepository->get($id);
            if($order->getStatus() != 'canceled')
            {
                $order->cancel();
                $order->setState(\Magento\Sales\Model\Order::STATE_CANCELED);
                $order->setStatus(\Magento\Sales\Model\Order::STATE_CANCELED);
                $order->save();
                $responseOrder->setOrderCancel('cenceled order id');
            }else{
                $responseOrder->setOrderCancel('order id been cenceled before');
            }
        } catch (\Exception $exception) {
            $responseOrder->setOrderCancel('Something were wrong 500');
        }

        return $responseOrder;
    }

    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\ResponseOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function orderDetail(int $id) : mixed
    {
        try{
            $responseOrder = $this->responseOrderFactory->create();
            $order = $this->_orderCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'entity_id',
                $id
            );
            $responseOrder->setOrderDetail([json_encode($order->getFirstItem()->getData())]);
        } catch (\Exception $exception) {
            $responseOrder->setOrderCancel('Something were wrong 500');
        }
        return $responseOrder;
    }
}
