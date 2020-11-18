<?php declare(strict_types=1);

namespace YireoTraining\ExampleRepository\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use YireoTraining\ExampleRepository\Repository\ItemRepository;

class UpdateCommand extends Command
{
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * CreateCommand constructor.
     * @param ItemRepository $itemRepository
     * @param string|null $name
     */
    public function __construct(
        ItemRepository $itemRepository,
        string $name = null
    ) {
        parent::__construct($name);
        $this->itemRepository = $itemRepository;
    }

    protected function configure()
    {
        $this
            ->setName('example:update')
            ->setDescription('Update item')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'ID of item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = (int)$input->getOption('id');
        $item = $this->itemRepository->getById($id);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter the name: ', $item->getData('name'));
        $name = $helper->ask($input, $output, $question);
        $item->setData('name', $name);

        $question = new Question('Please enter the ID: ', $item->getData('product_id'));
        $productId = $helper->ask($input, $output, $question);
        $item->setData('product_id', (int)$productId);

        $this->itemRepository->save($item);

        $output->writeln('Successfull saved item');
    }
}
