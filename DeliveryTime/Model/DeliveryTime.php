<?php

namespace TUTJunior\DeliveryTime\Model;

use Magento\Framework\Model\AbstractModel;

class DeliveryTime extends AbstractModel
{
    public function _construct()
    {
        $this->_init(\TUTJunior\DeliveryTime\Model\ResourceModel\DeliveryTime::class);
    }
}
