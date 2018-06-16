<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundPartnership\Controller\IndexController
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $controller = new IndexController($locator);

        return $controller;
    }
}
