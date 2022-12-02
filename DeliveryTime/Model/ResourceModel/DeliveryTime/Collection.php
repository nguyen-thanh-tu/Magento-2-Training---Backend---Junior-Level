<?php

namespace TUTJunior\DeliveryTime\Model\ResourceModel\DeliveryTime;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'delivery_time_id';

    public function _construct()
    {
        $this->_init(\TUTJunior\DeliveryTime\Model\DeliveryTime::class,\TUTJunior\DeliveryTime\Model\ResourceModel\DeliveryTime::class);
    }
}
