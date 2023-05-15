<?php

namespace TUTJunior\CancelOrder\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Block\Adminhtml\Order\View\Info as OrderInfo;

class Action extends Column
{
    protected $orderInfo;

    public function __construct(
        ContextInterface           $context,
        UiComponentFactory         $uiComponentFactory,
        OrderInfo                   $orderInfo,
        OrderInterface             $orderInterface,
        array                      $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->orderInfo = $orderInfo;
        $this->orderInterface = $orderInterface;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            $fieldName = $this->getData("name");
            foreach ($dataSource["data"]["items"] as $key => $item) {
                $order = $this->orderInterface->loadByIncrementId($dataSource["data"]["items"][$key]['increment_id']);
                $text = '';
                if($fieldName === 'action') {
                    $text = 'View Order';
                } elseif ($fieldName === 'increment_id') {
                    $text = '#'.$dataSource["data"]["items"][$key]['increment_id'];
                }
                $dataSource["data"]["items"][$key][$fieldName] = "<a href='".$this->orderInfo->getUrl('sales/order/view', ['order_id' => $order->getEntityId()])."'>".$text."</a>";
            }
        }

        return $dataSource;
    }
}
