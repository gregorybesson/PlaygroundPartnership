<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Service\Partner;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class PartnerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new Partner($container);

        return $service;
    }
}
