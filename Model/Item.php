<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Model;

use Magento\Framework\Model\AbstractModel;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;

class Item extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ItemResourceModel::class);
    }
}
