<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Test\Live;

use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;
use YireoTraining\ExampleRepository\Model\ItemFactory;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;
use YireoTraining\ExampleRepository\Repository\ItemRepository;

class SimpleInteractionTest extends TestCase
{
    public function testCreateNewItem()
    {
        $uniqueName = 'foobar-'.time();

        $itemResourceModel = ObjectManager::getInstance()->get(ItemResourceModel::class);
        $itemFactory = ObjectManager::getInstance()->get(ItemFactory::class);
        $item = $itemFactory->create();
        $item->setData('name', $uniqueName);
        $itemResourceModel->save($item);

        $item = $itemFactory->create();
        $itemResourceModel->load($item, $uniqueName, 'name');
        $this->assertNotEmpty($item);
        $this->assertEquals($uniqueName, $item->getData('name'));
        $this->assertTrue((int)$item->getData('id') > 0);

        $itemResourceModel->delete($item);

        $item = $itemFactory->create();
        $this->assertEmpty($item->getData('name'));
        $itemResourceModel->load($item, $uniqueName, 'name');
        $this->assertEmpty($item->getData('name'));
    }
}
