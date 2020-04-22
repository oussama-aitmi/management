<?php

namespace App\Normalizer;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
/**
 * Class ConstraintViolationListNormalizer
 * @package App\Infrastructure\Normalizer
 */
final class ConstraintViolationListNormalizer implements NormalizerInterface
{

    /**
     * @param object $object
     * @param null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, $format = null, array $context = array()): array
    {

        [$messages, $violations] = $this->getMessagesAndViolations($object);

        return [
            "status"=> "error",
            'code' => 400,
            'title' => $context['title'] ?? 'An error occurred',
            'message' => $messages ? implode("\n", $messages) : '',
            'error' => $violations,
        ];
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return array
     */
    private function getMessagesAndViolations(ConstraintViolationListInterface $constraintViolationList): array
    {
        $violations = $messages = [];
        /** @var ConstraintViolation $violation */
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

    /**
     * @param mixed $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof ConstraintViolationListInterface;
    }
}