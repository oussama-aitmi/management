<?php
namespace App\Service;


use App\Traits\ApiResponseTrait;

abstract class AbstractService
{

    /**
     * Using Service trait Response methods
     */
    use ApiResponseTrait;


    /**
     * @param object $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalizeViolations($object, $format = null)
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
    public function getMessagesAndViolations($constraintViolationList): array
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
