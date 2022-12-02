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

    public function getOrderCancel()
    {
        return $this->_getData(self::DATA_NOTICE);
    }

    public function setOrderCancel(string $notice)
    {
        return $this->setData(self::DATA_NOTICE, $notice);
    }

    public function getOrderDetail()
    {
        return $this->_getData(self::DATA_ORDER);
    }

    public function setOrderDetail(array $order)
    {
        return $this->setData(self::DATA_ORDER, $order);
    }
}
