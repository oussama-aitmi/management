<?php


namespace App\Controller;


use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;


abstract class BaseController extends AbstractFOSRestController
{

    /**
     * @return User
     */
    protected function getUser()
    {
        return parent::getUser();
    }


    /**
     * @param       $request
     * @param       $class
     * @param array $context
     * @return mixed
     */
    public function deserialize($request, $class, array $context = [])
    {
        return $this->get('serializer')->deserialize($request->getContent(), $class, 'json' , $context);
    }

    /**
     * Serialize an entity object to array
     *
     * @param       $entity
     * @param array $group
     */
    public function serialize($entity, array $group = [])
    {
        return $this->get('serializer')->serialize($entity, 'json' , ['groups' => $group]);
    }

    /**
     * @param       $entity
     * @param array $group
     * @return mixed
     */
    public function normalize($entity, array $group = [])
    {
        return $this->get('serializer')->normalize($entity, null , ['groups' => $group]);
    }
}