<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\DataObject;

class OrderDetailReponse extends DataObject implements \TUTJunior\CancelOrder\Api\Data\OrderDetailReponseInterface
{

    public function getOrderDetai()
    {
        return $this->_getData('getOrderDetai');
    }

    public function setOrderDetai(array $orderDetai)
    {
        return $this->setData('getOrderDetai', $orderDetai);
    }
}
