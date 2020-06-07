<?php

namespace App\Service;


use App\Entity\MediaProduct;
use App\Repository\MediaProductRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaProductService extends AbstractService{


    protected $validator;

    private $repository;

    /**
     * VariationService constructor.
     *
     * @param ValidatorInterface      $validator
     * @param MediaProductRepository  $repository
     */
    public function __construct(ValidatorInterface $validator, MediaProductRepository $repository)
    {
        $this->validator = $validator;
        $this->repository = $repository;
    }

    /**
     * @param       $entities
     * @param array $errors
     * @param       $files
     */
    public function validateImages(&$entities, array &$errors, $files): void
    {
        foreach ($files['images'] as $key => $mediaTag) {
            if (isset($mediaTag) && $mediaTag instanceof UploadedFile){
                $document = new MediaProduct();
                $document->setFile($mediaTag);

                $validationReturn = $this->getDetailsViolations($this->validator->validate($document));
                if (!empty($validationReturn)){
                    $validation['images'][$key] = $validationReturn;
                    $validation['images'][$key]['key'] = $key;
                    $errors = array_merge($errors, $validation);
                }

                $entities['images'][] = $document;
            }
        }
    }
}
