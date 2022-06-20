<?php

declare(strict_types=1);

namespace Note;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Note\Controller\NoteController;

return [
    'router' => [
        'routes' => [
            'notes' => [
                'type'    => Literal::class,
                'may_terminate' => false,
                'options' => [
                    'route' => '/notes'
                ],
                'child_routes' => [
                    'list' => [
                        'type' => Literal::class,
                        'options' => [
                            'route' => '/list',
                            'defaults' => [
                                'controller' => NoteController::class,
                                'action' => 'list'
                            ]
                        ]
                    ],
                    'delete' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/delete[/:id]',
                            'defaults' => [
                                'controller' => NoteController::class,
                                'action' => 'delete',
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            //Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
