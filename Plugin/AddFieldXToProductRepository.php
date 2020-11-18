<?php

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use YireoTraining\ExampleRepository\Repository\ItemRepository;

class AddFieldXToProductRepository
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    private $useFooExtensionAttributes = false;

    /**
     * AddFieldXToProductRepository constructor.
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function beforeGetList(ProductRepositoryInterface $productRepository, SearchCriteriaInterface $searchCriteria)
    {
        $filterGroups = $searchCriteria->getFilterGroups();
        if ($filterGroups) {
            $this->useFooExtensionAttributes = true;
        }

        return $searchCriteria;
    }

    public function afterGetList(ProductRepositoryInterface $productRepository, SearchResultsInterface $searchResult)
    {
        if ($searchResult->getTotalCount() > 1000) {
            return $searchResult;
        }

        if (!$this->useFooExtensionAttributes) {
            return $searchResult;
        }

        $items = $searchResult->getItems();
        $newItems = [];
        foreach ($items as $item) {
            $newItems[] = $this->addFieldToItem($item);
        }

        $searchResult->setItems($newItems);
        return $searchResult;
    }

    /**
     * @param ProductInterface $product
     * @return ProductInterface
     */
    private function addFieldToItem(ProductInterface $product): ProductInterface
    {
        if (!$product->getExtensionAttributes()) {
            return $product;
        }

        if (!method_exists($product->getExtensionAttributes(), 'setFoo')) {
            return $product;
        }

        $product->getExtensionAttributes()->setFoo($this->getFooValue($product->getId()));
        return $product;
    }

    /**
     * @param int $productId
     * @return string
     */
    private function getFooValue(int $productId): string
    {
        $allItems = $this->itemRepository->getAllItems();
        $item = false;
        foreach ($allItems as $allItem) {
            if ((int)$allItem->getData('product_id') === $productId) {
                $item = $allItem;
                break;
            }
        }

        if (!$item) {
            return '';
        }

        return (string) $item->getData('name');
    }
}
