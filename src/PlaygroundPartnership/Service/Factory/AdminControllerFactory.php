<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Controller\AdminController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundPartnership\Controller\AdminController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new AdminController($locator);

        return $controller;
    }
}
