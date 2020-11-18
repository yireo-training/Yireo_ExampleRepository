<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YireoTraining\ExampleRepository\Model\ItemFactory;
use YireoTraining\ExampleRepository\Model\ResourceModel\Item as ItemResourceModel;

class DeleteCommand extends Command
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
            ->setName('example:delete')
            ->setDescription('Delete item')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'Delete an item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getOption('id');

        $item = $this->itemFactory->create();
        $this->itemResourceModel->load($item, $id, 'id');
        $this->itemResourceModel->delete($item);
        $output->writeln('Deleted item');
    }
}
