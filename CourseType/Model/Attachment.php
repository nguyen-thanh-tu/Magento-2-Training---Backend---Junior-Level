<?php

namespace TUTJunior\CourseType\Model;

use Magento\Framework\Model\AbstractModel;

class Attachment extends AbstractModel
{
    public function _construct()
    {
        $this->_init(\TUTJunior\CourseType\Model\ResourceModel\Attachment::class);
    }
}
