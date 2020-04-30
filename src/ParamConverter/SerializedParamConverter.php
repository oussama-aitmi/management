<?php


namespace App\ParamConverter;
/*

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;


class SerializedParamConverter implements ParamConverterInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(ParamConverter $configuration)
    {
        if (!$configuration->getClass()) {
            return false;
        }

        return true;
    }

    public function apply(Request $request, ParamConverter $configuration)
    {

        $class = $configuration->getClass();

        dd($configuration);

        try {
            $object = $this->serializer->deserialize(
                $request->getContent(),
                $class,
                'json'
            );
        }
        catch (JsonException $e) {
            throw new JsonException(sprintf('Could not deserialize request content to object of type "%s"',
                $class));
        }

        // set the object as the request attribute with the given name
        // (this will later be an argument for the action)
        $request->attributes->set($configuration->getName(), $object);

    }
}
*/
