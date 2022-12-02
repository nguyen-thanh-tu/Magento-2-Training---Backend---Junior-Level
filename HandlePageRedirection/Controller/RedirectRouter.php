<?php
declare(strict_types=1);

namespace TUTJunior\HandlePageRedirection\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\Action\Redirect;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;

class RedirectRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory
     * @param ResponseInterface $response
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ActionFactory $actionFactory,
        ResponseInterface $response,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Search\Model\ResourceModel\Query\CollectionFactory $searchQueryCollection,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Api\Filter $filter,
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->filter = $filter;
        $this->filterCroup = $filterGroup;
        $this->searchCriteria = $searchCriteria;
        $this->productRepository = $productRepository;
        $this->_messageManager = $messageManager;
        $this->storeManager = $storeManager;
        $this->searchQueryCollection = $searchQueryCollection;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->actionFactory = $actionFactory;
        $this->response = $response;
    }

    /**
     * @param RequestInterface $request
     * @return ActionInterface|null
     */
    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');
        $keywords = preg_split("/([\/,_,...]+)/", $identifier);

        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addAttributeToSelect('*');
        $categoryCollection->addIsActiveFilter();
        $categories = clone $categoryCollection;
        $percentages = [];
        foreach($categories as $key => $category)
        {
            foreach($keywords as $keyword)
            {
                similar_text($keyword, (string)$category->getUrlKey(), $percentage);
                if(empty($percentages[$key]) || $percentage > $percentages[$key])
                {
                    $percentages[$key] = $percentage;
                }
            }
        }
        if(max($percentages) >= 70)
        {
            $maxs = array_keys($percentages, max($percentages));
            $categoryTarget = ((array)$categoryCollection->getItems())[$maxs[0]];
            $this->_messageManager->addNoticeMessage(__("The path ".$identifier." does not exist. Are you looking for ".$categoryTarget->getName()));
            $this->response->setRedirect($categoryTarget->getUrl(), 301);
            $request->setDispatched(true);
            return $this->actionFactory->create(Redirect::class);
        }
        $searchQuery = $this->searchQueryCollection->create()
            ->addFieldToFilter('store_id', $this->storeManager->getStore()->getId())
            ->addFieldToFilter('query_text', $keywords);
        if(!empty($searchQuery->getItems()))
        {
            $queryText = $searchQuery->getLastItem()->getQueryText();
            $this->response->setRedirect('catalogsearch/result/?q='.$queryText, 301);
            $request->setDispatched(true);
            return $this->actionFactory->create(Redirect::class);
        }
        foreach($keywords as $keyword)
        {
            if(strlen($keyword) < 3){continue;}
            $filter = clone $this->filter;
            $filterCroup = clone $this->filterCroup;
            $searchCriteria = clone $this->searchCriteria;
            $productRepository = clone $this->productRepository;
            $filter->setData('field','sku');
            $filter->setData('value',$keyword.'%');
            $filter->setData('condition_type','like');
            $filterCroup->setData('filters', [$filter]);
            $searchCriteria->setFilterGroups([$filterCroup]);
            $result = $productRepository->getList($searchCriteria);
            $products = $result->getItems();
            if(!empty($products))
            {
                $this->response->setRedirect('catalogsearch/result/?q='.$keyword, 301);
                $request->setDispatched(true);
                return $this->actionFactory->create(Redirect::class);
            }
        }

        return null;
    }
}
