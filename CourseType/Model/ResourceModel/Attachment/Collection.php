<?php

namespace TUTJunior\CourseType\Model\ResourceModel\Attachment;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    public function _construct()
    {
        $this->_init(\TUTJunior\CourseType\Model\Attachment::class,\TUTJunior\CourseType\Model\ResourceModel\Attachment::class);
    }
}
