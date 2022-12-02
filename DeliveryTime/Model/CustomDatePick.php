<?php

namespace TUTJunior\DeliveryTime\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\ScopeInterface;

class CustomDatePick implements ConfigProviderInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfiguration;


    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     * @codeCoverageIgnore
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration,
        \TUTJunior\DeliveryTime\Model\ResourceModel\DeliveryTime\CollectionFactory $deliveryTimeCollection,
        \Magento\Framework\Serialize\SerializerInterface $jsonSerializer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->scopeConfiguration = $scopeConfiguration;
        $this->deliveryTimeCollection = $deliveryTimeCollection;
        $this->jsonSerializer = $jsonSerializer;
        $this->storeManager = $storeManager;
        $this->_customerSession = $customerSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $showHide = [];
        $deliveryTimeCollection = $this->deliveryTimeCollection->create();
        $deliveryTimeOption = [];

        foreach($deliveryTimeCollection as $deliveryTime)
        {
            $deliveryTimeStoreView = $deliveryTime->getStoreView();
            $deliveryTimeCustomerGroup = $deliveryTime->getCustomerGroup();
            if (
                in_array($this->storeManager->getStore()->getId(), $this->jsonSerializer->unserialize($deliveryTimeStoreView)) ||
                in_array($this->_customerSession->getCustomer()->getGroupId(), $this->jsonSerializer->unserialize($deliveryTimeCustomerGroup))
            )
            {
                foreach($this->jsonSerializer->unserialize($deliveryTime->getRangeTime()) as $rangeTime)
                {
                    $deliveryTimeOption[] = (object)[
                        'label' =>  $rangeTime['from'].':00 - '.$rangeTime['to'].':00',
                        'value' =>  $rangeTime['from'].' - '.$rangeTime['to']
                    ];
                }
            }
        }
        $enabled = $this->scopeConfiguration->getValue('learning_block_config/general/enabled', ScopeInterface::SCOPE_STORE);
        $maxDate = $this->scopeConfiguration->getValue('deliveryTime/general/maximum_waiting_time', ScopeInterface::SCOPE_STORE);
        $minDate = $this->scopeConfiguration->getValue('deliveryTime/general/minimum_waiting_time', ScopeInterface::SCOPE_STORE);
        $noDeliveryDay = $this->scopeConfiguration->getValue('deliveryTime/general/days_not_receiving_goods', ScopeInterface::SCOPE_STORE);
        $enableComments = $this->scopeConfiguration->getValue('deliveryTime/general/enable_comments', ScopeInterface::SCOPE_STORE);
        $showHide['show_hide_custom_block'] = ($enabled)?true:false;
        $showHide['max_date'] = $maxDate;
        $showHide['min_date'] = $minDate;
        $showHide['noDeliveryDay'] = explode(',',$noDeliveryDay);
        $showHide['deliveryTimeOption'] = $deliveryTimeOption;
        $showHide['enable_comments'] = $enableComments;
        return $showHide;
    }
}

