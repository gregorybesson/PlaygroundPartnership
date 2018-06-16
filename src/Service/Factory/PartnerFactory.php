<?php
namespace PlaygroundPartnership\Service\Factory;

use PlaygroundPartnership\Service\Partner;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PartnerFactory implements FactoryInterface
{
    /**
    * @param ServiceLocatorInterface $locator
    * @return \PlaygroundPartnership\Service\Partner
    */
    public function createService(ServiceLocatorInterface $locator)
    {
        $service = new Partner($locator);

        return $service;
    }
}
