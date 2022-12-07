<?php

namespace TUTJunior\CancelOrder\Api;

interface OrderHistoryRepostoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $customerId
     * @return \TUTJunior\CancelOrder\Api\ResponseOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrderHistoryByCustomerId(int $customerId);

    /**
     * Return a filtered product.
     *
     * @param int $customerId
     * @param int $id
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function cancelOrder(int $customerId, int $id);

    /**
     * Return a filtered product.
     *
     * @param int $customerId
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\Data\OrderDetailReponseInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function orderDetail(int $customerId, int $id);
}
