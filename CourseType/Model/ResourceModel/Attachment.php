<?php

namespace TUTJunior\CourseType\Model\ResourceModel;

class Attachment extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('attachment', 'entity_id');
    }
}
