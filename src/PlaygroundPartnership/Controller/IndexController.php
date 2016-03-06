<?php

namespace PlaygroundPartnership\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexController extends AbstractActionController
{
    protected $partnerService;

    /**
     *
     * @var ServiceManager
     */
    protected $serviceLocator;

    public function __construct(ServiceLocatorInterface $locator)
    {
        $this->serviceLocator = $locator;
    }

    public function getServiceLocator()
    {
        
        return $this->serviceLocator;
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }

    public function ajaxNewsletterAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();

        $identifier = $this->getEvent()->getRouteMatch()->getParam('id');
        $user = $this->zfcUserAuthentication()->getIdentity();
        $sp = $this->getPartnerService();

        $response->setContent(\Zend\Json\Json::encode(array(
                'success' => 0
        )));

        $partner = $sp->getPartnerMapper()->findById($identifier);

        if ($request->isPost() && $partner && $user) {
            $data = $this->getRequest()->getPost()->toArray();

            if ($this->getPartnerService()->updateNewsletter($data, $this->zfcUserAuthentication()->getIdentity(), $partner)) {
                $response->setContent(\Zend\Json\Json::encode(array(
                    'success' => 1
                )));
            }
        }

        return $response;
    }

    public function getPartnerService()
    {
        if (null === $this->partnerService) {
            $this->partnerService = $this->getServiceLocator()->get('playgroundpartnership_partner_service');
        }

        return $this->partnerService;
    }

    public function setPartnerService($service)
    {
        $this->partnerService = $service;

        return $this;
    }
}
