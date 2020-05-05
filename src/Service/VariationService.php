<?php

namespace App\Service;


use App\Repository\VariationRepository;
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
     * VariationService constructor.
     *
     * @param VariationRepository $variationRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(VariationRepository $variationRepository, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->variationRepository = $variationRepository;
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
}
