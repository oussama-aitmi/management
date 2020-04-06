<?php
namespace App\Service;


use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ConstraintViolationList;

class AbstractService
{
    /**
     * Using Service trait methods
     */

    /**
     * @var string
     */
    protected $entityName;

    /**
     *
     * @var $dataReplacements
     */
    protected $dataReplacements = [];

    /**
     *
     * @var $extraData
     */
    protected $extraData = array();

    public function _construct(EntityManager $em, ValidatorInterface $validator)
    {
        //$this->em = $em;
        //$this->validator = $validator;
    }



    public function validateResourceData($errors)
    {
        //$entity = $this->getFilter()->getFiltredEntity($data);

        //$errors = $this->validate($entity, $group);

        return [
            'errors' => (count($errors) > 0) ? $this->getViolationMessages($errors) : []
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
}
