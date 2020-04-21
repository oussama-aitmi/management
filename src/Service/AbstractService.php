<?php
namespace App\Service;


use App\Response\ApiResponseException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


abstract class AbstractService
{

    /**
     * Using Service trait methods
     */
    use ApiResponseTrait;

    protected $model;

    protected $em;

    protected $encoder;

    protected $validator;

    /**
     * @param EntityManagerInterface $em
     * @param $entityName
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    protected function save($object)
    {
        try {
            $this->em->persist($object);
            $this->em->flush();
        } catch (ORMException $e) {
            $this->renderFailureResponse($e);
        }
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

    /**
     * @param $entity
     * @throws ApiResponseException \ null
     */
    protected function validate($entity)
    {
        $errors = $this->validator->validate($entity);

        if (\count($errors)) {
            $this->renderFailureResponse($this->normalizeViolations($errors));
        }
    }

    /**
     * @param object $object
     * @param null $format
     * @param array $context
     * @return array
     */
    private function normalizeViolations($object, $format = null)
    {
        [$messages, $violations] = $this->getMessagesAndViolations($object);

        return [
            //'message' => $messages ? implode("\n", $messages) : 'Une erreur est survenue',
            'form' => $violations,
        ];
    }

    /**
     * @param $constraintViolationList
     * @return array
     */
    private function getMessagesAndViolations($constraintViolationList): array
    {
        $violations = $messages = [];

        foreach ($constraintViolationList as $violation) {
            $violations[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];

            $propertyPath = $violation->getPropertyPath();
            $messages[] = ($propertyPath ? $propertyPath.': ' : '').$violation->getMessage();
        }

        return [$messages, $violations];
    }

}
