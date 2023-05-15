<?php

namespace TUTJunior\CancelOrder\Model\ResourceModel\CancelOrder;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    public function _construct()
    {
        $this->_init(\TUTJunior\CancelOrder\Model\CancelOrder::class,\TUTJunior\CancelOrder\Model\ResourceModel\CancelOrder::class);
    }
}
