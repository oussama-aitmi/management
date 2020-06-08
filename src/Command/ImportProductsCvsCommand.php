<?php declare(strict_types = 1);

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function fclose;
use function fgetcsv;
use function file_exists;
use function fopen;
use function is_readable;
use const PHP_EOL;

final class ImportProductsCvsCommand extends Command
{
    private const BATCH_SIZE = 2;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setName('import:products:import')
            ->addArgument('file', InputArgument::REQUIRED, 'Location of the CSV-file to read products from.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filename = $input->getArgument('file');


        $io = new SymfonyStyle($input, $output);
        $io->title('Product Importer');
        $io->text([
            'Reads a CSV file and imports the contained products into our database.',
            'This command will alter your database! Please be careful when using it in production.',
        ]);

        if(!file_exists($filename) || !is_readable($filename)) {
            $io->error(sprintf('The provided filename "%s" is not readable!', $filename));
            return 1;
        }


        $handle = fopen($filename, 'rb');
        $io->newLine();
        $name = '.';
        while (($row = fgetcsv($handle)) !== false) {
            $product= new Product();

            $product->setId($row[0])
                ->setUser($row[1])
                ->setName($row[2])
                ->setReference($row[3])
                ->setStatus($row[4])
                ->setBasePrice($row[5])
                ->setSellPrice($row[6]);

            if ($io->isVerbose()) {
                $name = (string) $product . PHP_EOL;
            }
            $io->write($name);

            $this->em->persist($product);
        }
        fclose($handle);
        $this->em->flush();
        $io->newLine();
        $io->success('Finished importing products.');

        return 0;
    }
}