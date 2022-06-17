<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Application\Controller\IndexController;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;

return [
    'db' => [
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=notes_project;host=127.0.0.1',
        'username' => 'root',
        'password' => ''
    ],
    'session_config' => [
        'cookie_lifetime' => 60 * 60 * 1,
        'gc_maxlifetime'     => 60 * 60 * 24 * 30,
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../../module/Application/view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../../module/Application/view/error/404.phtml',
            'error/index'             => __DIR__ . '/../../module/Application/view/error/index.phtml',
        ],
    ],

    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ]
    ]

];
