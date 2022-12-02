<?php

namespace TUTJunior\CancelOrder\Plugin\Block\Adminhtml\User\Edit\Tab;

use Magento\Store\Model\ResourceModel\Website\CollectionFactory as WebsiteCollectionFactory;

class Main
{
    private $registry;

    public function __construct(
        \Magento\Framework\Registry $registry,
        WebsiteCollectionFactory $websiteCollectionFactory
    ) {
        $this->registry = $registry;
        $this->websiteCollectionFactory = $websiteCollectionFactory;
    }

    /**
     * Get form HTML
     *
     * @return string
     */
    public function aroundGetFormHtml(
        \Magento\User\Block\User\Edit\Tab\Main $subject,
        \Closure $proceed
    ) {
        $websiteCollectionFactory = $this->websiteCollectionFactory->create();
        $form = $subject->getForm();
        if (is_object($form)) {
            $options = [];
            $options['0'] = __('--Select--');
            foreach($websiteCollectionFactory as $id => $website)
            {
                $options[$id] = __($website->getName());
            }
            $baseFieldset = $form->getElement('base_fieldset');
            /** @var $model \Magento\User\Model\User */
            $model = $this->registry->registry('permissions_user');
            $data = $model->getData();
            $baseFieldset->addField(
                'website_role',
                'select',
                [
                    'name' => 'website_role',
                    'label' => __('Select Manager'),
                    'title' => __('Select Manager'),
                    'options' => $options,
                    'class' => 'select',
                    'value' => isset($data['website_role']) ? $data['website_role'] : 0
                ]
            );

            $subject->setForm($form);
        }

        return $proceed();
    }
}
