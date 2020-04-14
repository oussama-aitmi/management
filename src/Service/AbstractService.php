<?php
namespace App\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;
//use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ConstraintViolationList;
use App\Traits\ApiResponseTrait;
use TTP\Response\ApiResponse;


class AbstractService
{

    /**
     * Using Service trait methods
     */
    use ApiResponseTrait;


    /**
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var $extraData
     */
    protected $extraData = array();

    /**
     * @param EntityManager $em
     */
    public function _construct(EntityManager $em)
    {
        /** @var EntityManager $em */
        $this->em = $em;
    }

    public function validateData($errors)
    {
        //$entity = $this->getFilter()->getFiltredEntity($data);

        //$errors = $this->validate($entity, $group);

        return [
            'error' => (count($errors) > 0) ? $this->getViolationMessages($errors) : []
        ];
    }

    /**
     * Prepare validation returned errors messages
     *
     * @param ConstraintViolationList $errors
     * @return array
     */
    public function getViolationMessages(ConstraintViolationList $errors)
    {
        $messages = [];
        foreach ($errors as $error) {
            $messages[] = [
                'code'         => !empty($error->getConstraint()->payload)
                    ? $error->getConstraint()->payload : $error->getCode(),
                'message'      => $error->getMessage(),
                'field'        => $error->getPropertyPath(),
                'invalidValue' => $error->getInvalidValue()
            ];
        }
        return $messages;
    }

    private function getErrorsFromValidator($errors)
    {
        $formattedErrors = [];
        foreach ($errors as $error) {
            $formattedErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        return $formattedErrors;
    }

    protected function save($object)
    {
        $this->em->persist($object);
        $this->em->flush();
    }

    protected function delete($object)
    {
        $this->em->remove($object);
        $this->em->flush();
    }

    protected function entityManager()
    {
        return $this->em;
    }
}
