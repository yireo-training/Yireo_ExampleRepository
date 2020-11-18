<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Item extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('example', 'id');
    }
}
