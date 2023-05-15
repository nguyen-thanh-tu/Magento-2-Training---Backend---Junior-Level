<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\DataObject;
use TUTJunior\CancelOrder\Api\RequestOrderInterface;

class RequestOrder extends DataObject implements RequestOrderInterface
{

    public function getId()
    {
        return $this->_getData(self::DATA_ID);
    }

    public function setId(int $id)
    {
        return $this->setData(self::DATA_ID, $id);
    }
}
