<?php

namespace TUTJunior\ExtendProductListing\Ui\Component\Massaction;

use Magento\Ui\Component\MassAction;

class AssignRelatedProduct extends MassAction
{
    public function prepare()
    {
        parent::prepare();

        if ($this->isEnabled()) {
            $config = $this->getConfiguration();
            $config['actions'][] = [
                'component' => 'uiComponent',
                'type' => 'text',
                'label' => 'Custom',
                'url' => 'testtunt'
            ];
            $this->setData('config', $config);
        }
    }

    public function isEnabled()
    {
        return true; // access your configuration here
    }
}

