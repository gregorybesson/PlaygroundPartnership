<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Controller\AdminController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class AdminControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new AdminController($container);

        return $controller;
    }
}
