<?php

namespace TUTJunior\CancelOrder\Api;

interface OrderHistoryRepostoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\ResponseOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrderHistoryByCustomerId(int $id);

    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\ResponseOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function cancelOrder(int $id);

    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\ResponseOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function orderDetail(int $id);
}
