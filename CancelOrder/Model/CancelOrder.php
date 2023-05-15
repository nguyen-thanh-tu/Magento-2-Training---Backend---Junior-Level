<?php

namespace TUTJunior\CancelOrder\Model;

use Magento\Framework\Model\AbstractModel;

class CancelOrder extends AbstractModel
{
    public function _construct()
    {
        $this->_init(\TUTJunior\CancelOrder\Model\ResourceModel\CancelOrder::class);
    }
}
