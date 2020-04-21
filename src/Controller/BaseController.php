<?php


namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use \FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;


abstract class BaseController extends AbstractFOSRestController
{

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