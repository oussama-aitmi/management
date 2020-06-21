<?php

namespace App\Service;


use App\Entity\Product;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TagService extends AbstractService{

    public $tagRepository;

    protected $validator;

    private $em;

    /**
     * TagService constructor.
     *
     * @param TagRepository $tagRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(TagRepository $tagRepository, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->validator = $validator;
        $this->tagRepository = $tagRepository;
        $this->em = $em;
    }

    /**
     * @param       $data
     * @param       $entities
     * @param array $errors
     */
    public function validateTags($data, &$entities, array &$errors): void
    {
        if (isset($data['tags']) && !empty($tags = $data['tags'])) {
            foreach ($tags as $key => $tag) {
                $tagEntity = $this->tagRepository->loadData($tag);
                $validationReturn = $this->getDetailsViolations($this->validator->validate($tagEntity));
                if (!empty($validationReturn)){
                    $validation['tags'][$key] = $validationReturn;
                    $validation['tags'][$key]['key'] = $key;
                    $errors = array_merge($errors, $validation);
                }

                $entities['tags'][] = $tagEntity;
            }
        }
    }

    /**
     * @param Product $product
     * @param         $entities
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveTags(Product $product, $entities)
    {
        if (!isset($entities['tags'])) {
            return;
        }

        $tags = $this->tagRepository->findBy(["product" => $product->getId()]);

        if ($tags) {
            foreach ($tags as $tag) {
                $this->em->remove($tag);
            }

            $this->em->flush();
        }

        if (empty($entities['tags'])) {
            return;
        }

        foreach ($entities['tags'] as $tag) {
            $product->addTag($tag);
            $this->em->persist($product);
        }

        $this->em->flush();
    }
}
