<?php

namespace TUTJunior\CancelOrder\Api;

interface ResponseOrderInterface
{
    const DATA_HISTORY = 'order_history';
    const DATA_NOTICE = 'notice';
    const DATA_ORDER = 'order_detail';
    /**
     * @return string
     */
    public function getOrderHistory();

    /**
     * @param array $order
     * @return $this
     */
    public function setOrderHistory(array $order);

    /**
     * @return string
     */
    public function getOrderCancel();

    /**
     * @param string $notice
     * @return $this
     */
    public function setOrderCancel(string $notice);

    /**
     * @return string
     */
    public function getOrderDetail();

    /**
     * @param array $order
     * @return $this
     */
    public function setOrderDetail(array $order);
}
