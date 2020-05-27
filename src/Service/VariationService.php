<?php

namespace App\Service;


use App\Entity\Product;
use App\Repository\VariationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VariationService extends AbstractService{

    /**
     * @var VariationRepository
     */
    public $variationRepository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * VariationService constructor.
     *
     * @param VariationRepository $variationRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(VariationRepository $variationRepository, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->variationRepository = $variationRepository;
        $this->em = $em;
    }

    /**
     * @param       $data
     * @param       $entities
     * @param array $errors
     */
    public function validateVariations($data, &$entities, array &$errors): void
    {
        if (isset($data['variations']) && !empty($variations = $data['variations'])) {
            foreach ($variations as $key => $variation) {
                $variationEntity = $this->variationRepository->loadData($variation);
                $validationReturn = $this->validator->validate($variationEntity);
                $validation['variations'][] = $this->getMessagesAndViolations($validationReturn);
                $entities['variations'][] = $variationEntity;
            }

            empty(array_filter($validation['variations'])) ?: $errors = array_merge($errors ,$validation);
        }
    }

    /**
     * @param Product $product
     * @param         $entities
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveVariations(Product $product, $entities)
    {
        if (!isset($entities['variations'])) {
            return;
        }

        $variations = $this->variationRepository->findBy(["product" => $product->getId()]);

        if ($variations) {
            foreach ($variations as $variation) {
                $this->em->remove($variation);
            }

            $this->em->flush();
        }

        if (empty($entities['variations'])) {
            return;
        }

        foreach ($entities['variations'] as $variation) {
            $product->addVariation($variation);
            $this->em->persist($product);
        }

        $this->em->flush();
    }
}
