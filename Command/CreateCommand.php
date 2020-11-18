<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YireoTraining\ExampleRepository\Model\ItemFactory;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;

class CreateCommand extends Command
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
     * CreateCommand constructor.
     * @param ItemResourceModel $itemResourceModel
     * @param ItemFactory $itemFactory
     * @param string|null $name
     */
    public function __construct(
        ItemResourceModel $itemResourceModel,
        ItemFactory $itemFactory,
        string $name = null
    ) {
        parent::__construct($name);
        $this->itemResourceModel = $itemResourceModel;
        $this->itemFactory = $itemFactory;
    }

    protected function configure()
    {
        $this
            ->setName('example:create')
            ->setDescription('Create item')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name of item')
            ->addOption('product_id', null, InputOption::VALUE_REQUIRED, 'Product ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = (string)$input->getOption('name');
        $productId = (int)$input->getOption('product_id');

        $item = $this->itemFactory->create();
        $item->setData('name', $name);
        $item->setData('product_id', $productId);

        $this->itemResourceModel->save($item);
        $output->writeln('Successfull saved item');
    }
}
