<?php declare(strict_types = 1);

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\Client;
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

    private $em;

    private $indexDefinition;

    private $client;

    public function __construct(EntityManagerInterface $em, array $indexDefinition, Client $client)
    {
        parent::__construct();
        $this->em = $em;
        $this->indexDefinition = $indexDefinition;
        $this->client = $client;
    }

    protected function configure()
    {
        $this
            ->setName('import:products:import')
            ->addArgument('file', InputArgument::REQUIRED, 'Location of the CSV-file to read products from.');
    }

    /**
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('CREATING Elastic INDEX...');
        $this->createIndex();

        $io->note('Importing Data to DB and FEEDING Elastic...');
        $this->importingData();

        $io->success('FEEDING DONE');

        return 0;
    }*/

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    private function createIndex(): void
    {
        if ($this->client->indices()->exists($this->indexDefinition)){
            $this->client->indices()->delete($this->indexDefinition);
        }

        $this->client->indices()->create(
            array_merge(
                $this->indexDefinition,
                [
                    'body' => [
                        'settings' => [
                            'number_of_shards' => 1,
                            'number_of_replicas' => 0,
                            "analysis" => [
                                "analyzer" => [
                                    "autocomplete" => [
                                        "tokenizer" => "autocomplete",
                                        "filter" => ["lowercase"]
                                    ]
                                ],
                                "tokenizer" => [
                                    "autocomplete" => [
                                        "type" => "edge_ngram",
                                        "min_gram" => 2,
                                        "max_gram" => 20,
                                        "token_chars" => [
                                            "letter",
                                            "digit"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "mappings" => [
                            "properties" => [
                                "title" => [
                                    "type" => "text",
                                    "analyzer" => "autocomplete",
                                    "search_analyzer" => "standard"
                                ]
                            ]
                        ]
                    ]
                ]
            )
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->note('CREATING Elastic INDEX...');
        $this->createIndex();


        $filename = $input->getArgument('file');

        $io = new SymfonyStyle($input, $output);

        $io->note('Importing Data to DB and FEEDING Elastic...');
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
        $user = 1; #TODO change this by given User from Argument
        $reference = rand(18888, 9999999); #TODO temporary

        while (($row = fgetcsv($handle)) !== false) {
            list($rowIdx, $title, $price, $retailer, $rating, $desc) = $row;

            $product= new Product();

            $product->setUser($user)
                ->setName($title)
                ->setReference($reference)
                ->setStatus(1)
                ->setBasePrice($price)
                ->setSellPrice($price)
            ->setSmallDescription($desc);

            if ($io->isVerbose()) {
                //$name = (string) $product . PHP_EOL;
                $name = (string) $title . PHP_EOL;

            }
            $io->write($name);

            $this->em->persist($product);
            $this->em->flush(); //purpose making flush inside boucle is to get Id

            //Update Elastica
            $doc = array_merge(
                $this->indexDefinition,
                [
                    'id' => $product->getId(),
                    'body' => [
                        'name' => $title,
                        'reference' =>$reference,
                        'basePrice' => (float)$price,
                        'sellPrice' => $price,
                        'smallDescription' => $desc
                    ]
                ]
            );
            $this->client->index($doc);
        }
        fclose($handle);
        $io->newLine();
        $io->success('Finished importing products.');

        return 0;
    }
}