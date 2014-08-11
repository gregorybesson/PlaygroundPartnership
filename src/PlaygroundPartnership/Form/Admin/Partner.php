<?php

namespace PlaygroundPartnership\Form\Admin;

use Zend\Form\Form;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;
use Zend\Mvc\I18n\Translator;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\ServiceManager\ServiceManager;

class Partner extends ProvidesEventsForm
{
    protected $serviceManager;

    public function __construct($name = null, ServiceManager $serviceManager, Translator $translator)
    {
        parent::__construct($name);

        $entityManager = $serviceManager->get('playgroundpartnership_doctrine_em');

        // The form will hydrate an object
        // This is the secret for working with collections with Doctrine
        // (+ add'Collection'() and remove'Collection'() and "cascade" in corresponding Entity
        // https://github.com/doctrine/DoctrineModule/blob/master/docs/hydrator.md
        //$this->setHydrator(new DoctrineHydrator($entityManager, 'PlaygroundPartnership\Entity\Partner'));

        parent::__construct();
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'id',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 0,
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'options' => array(
                'label' => $translator->translate('Name', 'playgroundpartnership'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Name', 'playgroundpartnership'),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'active',
            'options' => array(
                //'empty_option' => $translator->translate('Is the answer correct ?', 'playgroundpartnership'),
                'value_options' => array(
                    '0' => $translator->translate('No', 'playgroundpartnership'),
                    '1' => $translator->translate('Yes', 'playgroundpartnership'),
                ),
                'label' => $translator->translate('Active', 'playgroundpartnership'),
            ),
        ));

        // Adding an empty upload field to be able to correctly handle this on the service side.
        $this->add(array(
            'name' => 'uploadLogo',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => $translator->translate('Logo', 'playgroundpartnership'),
            ),
        ));
        $this->add(array(
            'name' => 'logo',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                    'value' => '',
            ),
        ));

        // Adding an empty upload field to be able to correctly handle this on the service side.
        $this->add(array(
            'name' => 'uploadSmallLogo',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => $translator->translate('Small logo', 'playgroundpartnership'),
            ),
        ));

        $this->add(array(
            'name' => 'smallLogo',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => '',
            ),
        ));

        $this->add(array(
            'name' => 'website',
            'options' => array(
                'label' => $translator->translate('Web site', 'playgroundpartnership'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Web site', 'playgroundpartnership'),
            ),
        ));

        $this->add(array(
            'name' => 'facebook',
            'options' => array(
                'label' => $translator->translate('Facebook Page', 'playgroundpartnership'),
            ),
            'attributes' => array(
                'type' => 'text',
                'placeholder' => $translator->translate('Facebook Page', 'playgroundpartnership'),
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'newsletter',
            'options' => array(
            //'empty_option' => $translator->translate('Is the answer correct ?', 'playgroundpartnership'),
                'value_options' => array(
                    '0' => $translator->translate('No', 'playgroundpartnership'),
                    '1' => $translator->translate('Yes', 'playgroundpartnership'),
                ),
                'label' => $translator->translate('Active newsletter registration', 'playgroundpartnership'),
            ),
        ));

        $this->add(array(
            'name' => 'bouncePage',
            'type' => 'Zend\Form\Element\Radio',
            'options' => array(
                'label' => $translator->translate('Recirculation page', 'playgroundpartnership'),
                'value_options' => array(
                    '1' => $translator->translate('Yes', 'playgroundpartnership'),
                    '0' => $translator->translate('No', 'playgroundpartnership'),
                ),
            ),
        ));

        $submitElement = new Element\Button('submit');
        $submitElement
        ->setAttributes(array(
            'type'  => 'submit',
        ));

        $this->add($submitElement, array(
            'priority' => -100,
        ));

    }
}
