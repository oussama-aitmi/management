<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ExportProductsCsvCommand extends Command
{
    protected static $defaultName = 'export:products:csv';

    private $em;

    private $serializer;

    private  $filesystem;

    public function __construct(EntityManagerInterface $em, NormalizerInterface $serializer, Filesystem $filesystem)
    {
        parent::__construct();
        $this->em = $em;
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Product export to a csv file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $output->writeln('initializing export...');

        $products =  $this->em->getRepository(Product::class)->findAll();

        $progressBar = new ProgressBar($output, count($products));
        $progressBar->start();
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% - %message:5s%');

        $data = [];
        foreach($products as $product) {

            /** @var Product $product */
            $productName = $product->getName();

            $data[] = [
                'id'                    => $product->getId(),
                'name'                  => $product->getName(),
                'reference'             => $product->getReference(),
                'base_price'            => $product->getBasePrice(),
                'status'                => $product->getStatus(),
                'status_store'          => $product->getStatusStore(),
                'slug'                  => $product->getSlug(),
                'sell_price'            => $product->getSellPrice(),
                'small_description'     => $product->getSmallDescription(),
                'quantity'              => $product->getQuantity(),
                'minimum_sales_quantity'=> $product->getMinimumSalesQuantity(),
                'maximum_sales_quantity'=> $product->getMaximumSalesQuantity(),
                'user'                  => $product->getUser()->getId()
            ];

            $progressBar->setMessage("Processing '${productName}'");
            $progressBar->advance();
        }

        $csvData = $this->serializer->encode($data, 'csv');
        $this->filesystem->dumpFile('products', $csvData);
        $output->writeln('');
        $output->writeln('Done !');

        $progressBar->finish();
    }
}