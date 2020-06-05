<?php

namespace App\Service;


use App\Entity\MediaProduct;
use App\Repository\MediaProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaProductService extends AbstractService{


    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MediaProductRepository
     */
    private $repository;

    /**
     * VariationService constructor.
     *
     * @param ValidatorInterface     $validator
     * @param MediaProductRepository           $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(ValidatorInterface $validator, MediaProductRepository $repository, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @param       $data
     * @param       $entities
     * @param array $errors
     * @param       $files
     */
    public function validateImages($data, &$entities, array &$errors, $files): void
    {
        if (isset ($files['images']) && $files['images'] instanceof UploadedFile){

            $document = new MediaProduct();
            $document->setFile($files['images']);

            $validationReturn = $this->validator->validate($document);
            $validation['images'][] = $this->getMessagesAndViolations($validationReturn);
            $entities['images'] = $document;


            empty(array_filter($validation['images'])) ?: $errors = array_merge($errors ,$validation);
        }
    }

}
