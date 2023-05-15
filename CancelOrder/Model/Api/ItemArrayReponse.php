<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\DataObject;
use TUTJunior\CancelOrder\Api\Data\ItemArrayReponseInterface;

class ItemArrayReponse extends DataObject implements \TUTJunior\CancelOrder\Api\Data\ItemArrayReponseInterface
{

    public function getItemArray()
    {
        return $this->_getData('getItemArray');
    }

    public function setItemArray(array $item)
    {
        return $this->setData('getItemArray', $item);
    }
}
