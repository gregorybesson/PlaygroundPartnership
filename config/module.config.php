<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'playgroundpartnership_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/PlaygroundPartnership/Entity'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'PlaygroundPartnership\Entity'  => 'playgroundpartnership_entity'
                )
            )
        )
    ),

    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type'         => 'phpArray',
                'base_dir'     => __DIR__ . '/../language',
                'pattern'      => '%s.php',
                'text_domain'  => 'playgroundpartnership'
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view/admin',
        	__DIR__ . '/../view/frontend',
        ),
    ),
	
	'core_layout' => array(
		'PlaygroundPartnership' => array(
			'default_layout' => 'layout/2columns-left',
			'controllers' => array(
				'playgroundpartnership_admin' => array(
					'default_layout' => 'layout/admin',
				),
			),
		),
	),

    'controllers' => array(
        'invokables' => array(
            'playgroundpartnership_admin' => 'PlaygroundPartnership\Controller\AdminController',
            'playgroundpartnership'      => 'PlaygroundPartnership\Controller\IndexController',
        ),
    ),

    'router' => array(
        'routes' => array(
        	'frontend' => array(
       			'child_routes' => array(        		
		            'partnership' => array(
		                'type' => 'Zend\Mvc\Router\Http\Segment',
		                'options' => array(
		                    'route'    => 'partnership[/:id]',
		                    'defaults' => array(
		                        'controller' => 'playgroundpartnership',
		                        'action'     => 'index',
		                    ),
		                ),
		                'may_terminate' => true,
		                'child_routes' =>array(
		                    'share' => array(
		                        'type' => 'Literal',
		                        'options' => array(
		                            'route' => '/share',
		                            'defaults' => array(
		                                'controller' => 'playgroundpartnership',
		                                'action'     => 'share'
		                            ),
		                        ),
		                    ),
		                    'ajax_newsletter' => array(
		                        'type' => 'Literal',
		                        'options' => array(
		                            'route' => '/ajax-newsletter',
		                            'defaults' => array(
		                                'controller' => 'playgroundpartnership',
		                                'action'     => 'ajaxNewsletter',
		                            ),
		                        ),
		                    ),
		                ),
		            ),
       			),
        	),
            'admin' => array(
                'child_routes' => array(
                    'playgroundpartnership_admin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/partnership',
                            'defaults' => array(
                                'controller' => 'playgroundpartnership_admin',
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list[/:p]',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'list',
                                    ),
                                ),
                            ),
                            'create' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/create',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'create'
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:partnerId',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'edit',
                                        'partnerId'     => 0
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:partnerId',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'remove',
                                        'partnerId'     => 0
                                    ),
                                ),
                            ),
                            'newsletter' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/newsletter/:partnerId[/:p]',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'newsletter',
                                    ),
                                ),
                            ),
                            'download' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/download/:partnerId',
                                    'defaults' => array(
                                        'controller' => 'playgroundpartnership_admin',
                                        'action'     => 'download',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'navigation' => array(
        'admin' => array(
            'playgroundpartnershipadmin' => array(
                'order' => 80,
                'label' => 'Partners',
                'route' => 'admin/playgroundpartnership_admin/list',
                'resource' => 'partner',
                'privilege' => 'list',
                'pages' => array(
                    'list' => array(
                        'label' => 'Partners list',
                        'route' => 'admin/playgroundpartnership_admin/list',
                        'resource' => 'partner',
                        'privilege' => 'list',
                    ),
                    'create' => array(
                        'label' => 'Create partner',
                        'route' => 'admin/playgroundpartnership_admin/create',
                        'resource' => 'partner',
                        'privilege' => 'list',
                    ),
                ),
            ),
        ),
    )
);
