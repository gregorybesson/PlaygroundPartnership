<?php
namespace PlaygroundPartnership;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{

    public function onBootstrap (MvcEvent $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $options = $serviceManager->get('playgroundcore_module_options');
        $locale = $options->getLocale();
        $translator = $serviceManager->get('translator');
        if (!empty($locale)) {
            //translator
            $translator->setLocale($locale);

            // plugins
            $translate = $serviceManager->get('viewhelpermanager')->get('translate');
            $translate->getTranslator()->setLocale($locale);
        }
        AbstractValidator::setDefaultTranslator($translator,'playgroundcore');

        // If PlaygroundGame is installed, I can add my own partners to benefit from
        // this feature
        $e->getApplication()
            ->getEventManager()
            ->getSharedManager()
            ->attach('Zend\Mvc\Application', 'getPartners', array(
            $this,
            'updatePartners'
        ));
    }

    /**
     * This method get the partners and add them as array to PlaygroundGame form so
     * that there is non adherence between modules...
     * not that satisfied
     *
     * @param  EventManager $e
     * @return array
     */
    public function updatePartners ($e)
    {
        $partnersArray = $e->getParam('partners');

        $partnerService = $e->getTarget()
            ->getServiceManager()
            ->get('playgroundpartnership_partner_service');
        $partners = $partnerService->getActivepartners();

        foreach ($partners as $partner) {
            $partnersArray[$partner->getId()] = $partner->getName();
        }

        return $partnersArray;
    }

    public function getConfig ()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig ()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'partnerSubscriber' => function($sm) {
                $locator = $sm->getServiceLocator();
                $viewHelper = new View\Helper\PartnerSubscriber;
                $viewHelper->setPartnerService($locator->get('playgroundpartnership_partner_service'));

                return $viewHelper;
                },
            ),
        );
    }

    public function getServiceConfig ()
    {
        return array(
            'aliases' => array(
                'playgroundpartnership_doctrine_em' => 'doctrine.entitymanager.orm_default'
            ),

            'invokables' => array(
                'playgroundpartnership_partner_service' => 'PlaygroundPartnership\Service\Partner'
            ),

            'factories' => array(
                'playgroundpartnership_module_options' => function  ($sm) {
                    $config = $sm->get('Configuration');

                    return new Options\ModuleOptions(isset($config['playgroundpartnership']) ? $config['playgroundpartnership'] : array());
                },
                'playgroundpartnership_partner_mapper' => function  ($sm) {
                    return new Mapper\Partner($sm->get('playgroundpartnership_doctrine_em'), $sm->get('playgroundpartnership_module_options'));
                },
                'playgroundpartnership_subscriber_mapper' => function  ($sm) {
                    return new Mapper\Subscriber($sm->get('playgroundpartnership_doctrine_em'), $sm->get('playgroundpartnership_module_options'));
                },
                'playgroundpartnership_partner_form' => function  ($sm) {
                    $translator = $sm->get('translator');
                    $form = new Form\Admin\Partner(null, $sm, $translator);
                    $partner = new Entity\Partner();
                    $form->setInputFilter($partner->getInputFilter());

                    return $form;
                }
            )
        );
    }
}
