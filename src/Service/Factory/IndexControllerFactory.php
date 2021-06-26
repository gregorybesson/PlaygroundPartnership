<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Controller\IndexController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $controller = new IndexController($container);

        return $controller;
    }
}
