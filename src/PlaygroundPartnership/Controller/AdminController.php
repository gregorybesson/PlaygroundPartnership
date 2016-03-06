<?php

namespace PlaygroundPartnership\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use PlaygroundPartnership\Options\ModuleOptions;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdminController extends AbstractActionController
{
    protected $options;
    protected $partnerMapper;
    protected $adminActionService;
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

    public function listAction()
    {
        $partnerMapper = $this->getPartnerMapper();
        $partners = $partnerMapper->findAll();
        if (is_array($partners)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($partners));
        } else {
            $paginator = $partners;
        }

        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));

        return array(
            'partners' => $paginator,
        );
    }

    public function newsletterAction()
    {
        $partnerId = $this->getEvent()->getRouteMatch()->getParam('partnerId');
        $partner = $this->getPartnerMapper()->findById($partnerId);

        $subscriberMapper = $this->getAdminPartnerService()->getSubscriberMapper();
        $subscribers = $subscriberMapper->findBy(array('partner' => $partner));
        if (is_array($subscribers)) {
            $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($subscribers));
        } else {
            $paginator = $subscribers;
        }

        $paginator->setItemCountPerPage(100);
        $paginator->setCurrentPageNumber($this->getEvent()->getRouteMatch()->getParam('p'));

        return array(
                'subscribers' => $paginator,
                'partner'     => $partner
        );
    }

    public function downloadAction()
    {
        // magically create $content as a string containing CSV data
        $partnerId = $this->getEvent()->getRouteMatch()->getParam('partnerId');
        $partner   = $this->getPartnerMapper()->findById($partnerId);

        $subscriberMapper = $this->getAdminPartnerService()->getSubscriberMapper();
        $subscribers = $subscriberMapper->findBy(array('partner' => $partner));

        $content        = "\xEF\xBB\xBF"; // UTF-8 BOM
        $content       .= "ID;Pseudo;Nom;Prenom;E-mail;Optin\n";
        foreach ($subscribers as $s) {
            $content   .= $s->getUser()->getId()
            . ";" . $s->getUser()->getUsername()
            . ";" . $s->getUser()->getLastname()
            . ";" . $s->getUser()->getFirstname()
            . ";" . $s->getUser()->getEmail()
            . ";" . $s->getActive()
            ."\n";
        }

        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Encoding: UTF-8');
        $headers->addHeaderLine('Content-Type', 'text/csv; charset=UTF-8');
        $headers->addHeaderLine('Content-Disposition', "attachment; filename=\"newsletter-". $partner->getName() .".csv\"");
        $headers->addHeaderLine('Accept-Ranges', 'bytes');
        $headers->addHeaderLine('Content-Length', strlen($content));

        $response->setContent($content);

        return $response;
    }

    public function createAction()
    {
        $form = $this->getServiceLocator()->get('playgroundpartnership_partner_form');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $partner = $this->getAdminPartnerService()->create($data, 'playgroundpartnership_partner_form');
            if ($partner) {
                $this->flashMessenger()->setNamespace('playgroundpartnership')->addMessage('The partner was created');

                return $this->redirect()->toRoute('admin/playgroundpartnership_admin/list');
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-partnership/admin/partner');

        return $viewModel->setVariables(array('form' => $form));
    }

    public function editAction()
    {
        $partnerId = $this->getEvent()->getRouteMatch()->getParam('partnerId');
        $partner = $this->getPartnerMapper()->findById($partnerId);

        $form = $this->getServiceLocator()->get('playgroundpartnership_partner_form');

        $request = $this->getRequest();

        $form->bind($partner);

        if ($request->isPost()) {
            $data = array_merge(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            $partner = $this->getAdminPartnerService()->edit($data, $partner, 'playgroundpartnership_partner_form');
            if ($partner) {
                $this->flashMessenger()->setNamespace('playgroundpartnership')->addMessage('The partner was updated');

                return $this->redirect()->toRoute('admin/playgroundpartnership_admin/list');
            }
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('playground-partnership/admin/partner');

        return $viewModel->setVariables(array('form' => $form));
    }

    public function removeAction()
    {
        // TODO : Remove occurences of this partner in the games
        $partnerId = $this->getEvent()->getRouteMatch()->getParam('partnerId');
        $partner = $this->getPartnerMapper()->findById($partnerId);
        if ($partner) {
            try {
                $this->getPartnerMapper()->remove($partner);
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->flashMessenger()->setNamespace('playgroundpartnership')->addMessage('Vous devez retirer ce partenaire des jeux pour pouvoir le supprimer');
            //throw $e;
            }

            $this->flashMessenger()->setNamespace('playgroundpartnership')->addMessage('The partner was deleted');
        }

        return $this->redirect()->toRoute('admin/playgroundpartnership_admin/list');
    }

    public function setOptions(ModuleOptions $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions()
    {
        if (!$this->options instanceof ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('playgroundpartnership_module_options'));
        }

        return $this->options;
    }

    public function getPartnerMapper()
    {
        if (null === $this->partnerMapper) {
            $this->partnerMapper = $this->getServiceLocator()->get('playgroundpartnership_partner_mapper');
        }

        return $this->partnerMapper;
    }

    public function setPartnerMapper(ActionMapperInterface $partnerMapper)
    {
        $this->partnerMapper = $partnerMapper;

        return $this;
    }

    public function getAdminPartnerService()
    {
        if (null === $this->adminActionService) {
            $this->adminActionService = $this->getServiceLocator()->get('playgroundpartnership_partner_service');
        }

        return $this->adminActionService;
    }

    public function setAdminPartnerService($service)
    {
        $this->adminActionService = $service;

        return $this;
    }
}
