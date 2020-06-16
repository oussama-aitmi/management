<?php

namespace App\Traits;

use App\Response\ApiResponseException;
use App\Response\ApiResponse;
use Symfony\Component\HttpFoundation\Response;


trait serialize
{
    /**
     * @param       $request
     * @param       $class
     * @param array $context
     * @return mixed
     */
    public function deserialize($request, $class, array $context = [])
    {
        #return $this->get('serializer')->deserialize($request->getContent(), $class, 'json' , $context);
    }

    /**
     * Serialize an entity object to array
     *
     * @param       $entity
     * @param array $group
     */
    public function serialize($entity, array $group = [])
    {
        //return $this->get('serializer')->serialize($entity, 'json' , ['groups' => $group]);
    }

    /**
     * @param       $entity
     * @param array $group
     * @return mixed
     */
    public function normalize($entity, array $group = [])
    {
        #return $this->get('serializer')->normalize($entity, null , ['groups' => $group]);
    }

}
