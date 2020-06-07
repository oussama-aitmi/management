<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Category;


class CategoryValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $this->validateCategory($value);
    }

    protected function validateCategory(Category $category)
    {
        //dd("validateCategory");
        return "dddddd";
        /*if ( $this->isNewEntity( $category->getParent() ) ) {
            $this->context->buildViolation('The Category does not exist-Validator')
                ->atPath('category')
                ->setInvalidValue(null)
                ->addViolation();
        }*/
    }

    private function isNewEntity($entity)
    {
        return \Doctrine\ORM\UnitOfWork::STATE_NEW == $this->em->getUnitOfWork()->getEntityState($entity);
    }

}
