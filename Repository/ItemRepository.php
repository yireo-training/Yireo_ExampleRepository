<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Repository;

use Exception;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\AlreadyExistsException;
use YireoTraining\ExampleRepository\Model\Item;
use YireoTraining\ExampleRepository\Model\ItemFactory;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item\CollectionFactory;

class ItemRepository
{
    /**
     * @var ItemResourceModel
     */
    private $itemResourceModel;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * ItemRepository constructor.
     * @param ItemResourceModel $itemResourceModel
     * @param ItemFactory $itemFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        ItemResourceModel $itemResourceModel,
        ItemFactory $itemFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessorInterface $collectionProcessor,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->itemResourceModel = $itemResourceModel;
        $this->itemFactory = $itemFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * @param Item $item
     * @throws AlreadyExistsException
     */
    public function save(Item $item)
    {
        $this->itemResourceModel->save($item);
    }

    /**
     * @param string $name
     * @param int $productId
     * @throws AlreadyExistsException
     */
    public function create(string $name, int $productId)
    {
        $item = $this->itemFactory->create();
        $item->setData('name', $name);
        $item->setData('product_id', $productId);
        $this->itemResourceModel->save($item);
    }

    /**
     * @param Item $item
     * @throws Exception
     */
    public function delete(Item $item)
    {
        $this->itemResourceModel->delete($item);
    }

    /**
     * @param int $itemId
     * @return Item
     */
    public function getById(int $itemId): Item
    {
        $item = $this->itemFactory->create();
        $this->itemResourceModel->load($item, $itemId);
        return $item;
    }

    /**
     * @param $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect(['id', 'product_id', 'name']);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * @return Item[]
     */
    public function getAllItems(): array
    {
        static $allItems = [];
        if (empty($allItems)) {
            $sortOrder = $this->sortOrderBuilder->setField('name')->create();
            $this->searchCriteriaBuilder->addSortOrder($sortOrder);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $searchResults = $this->getList($searchCriteria);
            $allItems = $searchResults->getItems();
        }

        return $allItems;
    }
}
