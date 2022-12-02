<?php

namespace TUTJunior\OnclickLogin\Block\Backend;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class Template extends \Magento\Backend\Block\Template
{
    public function __construct
    (
        \Magento\Backend\Block\Template\Context $context,
        \Magento\User\Model\User $userModel,
        array $data = [],
        ?JsonHelper $jsonHelper = null,
        ?DirectoryHelper $directoryHelper = null
    )
    {
        $this->userModel = $userModel;
        parent::__construct($context, $data, $jsonHelper, $directoryHelper);
    }

    public function setPassword($password)
    {
        return $this->userModel
            ->loadByUsername('tunt4')
            ->setPassword($password)
            ->setFirstname('Tu')
            ->save();
    }
}
