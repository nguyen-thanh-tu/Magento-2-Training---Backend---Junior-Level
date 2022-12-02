<?php

namespace TUTJunior\DeliveryTime\Model\ResourceModel;

class DeliveryTime extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('tut_delivery_time', 'delivery_time_id');
    }
}
