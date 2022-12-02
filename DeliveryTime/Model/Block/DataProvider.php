<?php

namespace TUTJunior\DeliveryTime\Model\Block;

use TUTJunior\DeliveryTime\Model\ResourceModel\DeliveryTime\CollectionFactory as DeliveryTimeCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var DeliveryTimeCollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * DataProvider constructor.
     * @param DeliveryTimeCollectionFactory $googleFeedCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        DeliveryTimeCollectionFactory $googleFeedCollectionFactory,
        DataPersistorInterface $dataPersistor,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $googleFeedCollectionFactory->create();
        $this->collectionFactory = $googleFeedCollectionFactory;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $this->loadedData[$item->getDeliveryTimeId()] = $item->getData();
            $this->loadedData[$item->getDeliveryTimeId()]['store_view'] = implode(',',json_decode($item->getStoreView()));
            $this->loadedData[$item->getDeliveryTimeId()]['customer_group'] = implode(',',json_decode($item->getCustomerGroup()));
            $this->loadedData[$item->getDeliveryTimeId()]['range_time'] = json_decode($item->getRangeTime());
        }
        return $this->loadedData;
    }
}
