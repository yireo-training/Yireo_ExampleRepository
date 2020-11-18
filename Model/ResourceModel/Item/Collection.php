<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use YireoTraining\ExampleRepository\Model\Item as ItemModel;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            ItemModel::class,
            ItemResourceModel::class
        );
    }

    protected function _beforeLoad()
    {
        $this->join('catalog_eav_attribute', ['main_table.product_id' => 'catalog_eav_attribute.entity_id'], ['sku']);
    }
}
