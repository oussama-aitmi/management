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
}