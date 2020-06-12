<?php

namespace App\Validator;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ProductValidator extends ConstraintValidator
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $this->validateCategory($value);
    }

    protected function validateCategory(Product $product)
    {
        if (!isset($product) || empty($product) || !$this->categoryRepository->findOneBy(['id'=> $product->getCategory()])) {
            $this->context->buildViolation('Catégorie est invalide')
                ->atPath('category')
                ->setInvalidValue(null)
                ->addViolation();
        }
    }
}
