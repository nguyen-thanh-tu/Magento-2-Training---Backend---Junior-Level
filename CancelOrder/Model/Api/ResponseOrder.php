<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\DataObject;
use TUTJunior\CancelOrder\Api\ResponseOrderInterface;

class ResponseOrder extends DataObject implements ResponseOrderInterface
{

    public function getOrderHistory()
    {
        return $this->_getData(self::DATA_HISTORY);
    }

    public function setOrderHistory(array $order)
    {
        return $this->setData(self::DATA_HISTORY, $order);
    }
}
