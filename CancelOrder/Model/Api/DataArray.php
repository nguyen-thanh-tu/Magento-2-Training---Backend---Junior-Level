<?php

namespace TUTJunior\CancelOrder\Model\Api;

use Magento\Framework\DataObject;

class DataArray extends DataObject implements \TUTJunior\CancelOrder\Api\DataArrayInterface
{

    public function getName()
    {
        return $this->_getData('nameTUTJunior');
    }

    public function setName(string $name)
    {
        return $this->setData('nameTUTJunior', $name);
    }

    public function getValue()
    {
        return $this->_getData('valueTUTJunior');
    }

    public function setValue($value)
    {
        return $this->setData('valueTUTJunior', $value);
    }
}
