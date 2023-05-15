<?php

namespace TUTJunior\CancelOrder\Api;

interface ResponseOrderInterface
{
    const DATA_HISTORY = 'order_history';
    const DATA_NOTICE = 'notice';
    const DATA_ORDER = 'order_detail';

    /**
     * @return \TUTJunior\CancelOrder\Api\Data\ItemArrayReponseInterface[]
     */
    public function getOrderHistory();

    /**
     * @param array $order
     * @return $this
     */
    public function setOrderHistory(array $order);
}
