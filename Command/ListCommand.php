<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Command;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YireoTraining\ExampleRepository\Repository\ItemRepository;

class ListCommand extends Command
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    public function __construct(
        ItemRepository $itemRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->itemRepository = $itemRepository;
    }

    protected function configure()
    {
        $this->setName('example:list')->setDescription('List items');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $items = $this->itemRepository->getAllItems();

        $table = new Table($output);
        foreach ($items as $item) {
            $table->addRow([$item->getData('id'), $item->getData('product_id'), $item->getData('name')]);
        }

        $table->render();
    }
}
