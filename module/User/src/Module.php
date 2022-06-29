<?php

declare(strict_types=1);

namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Session\SessionManager;
use Laminas\Validator\EmailAddress;
use PhpParser\Node\Expr\FuncCall;
use User\Controller\IndexController;
use User\Model\User;
use User\Model\UserTable;
use User\Service\AuthAdapter;
use User\Service\UserService;
use Laminas\Authentication\Storage\Session as SessionStorage;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Session\Container;
use User\Controller\AuthController;
use User\Controller\UserController;
use User\Service\AuthService;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();
        $sessionManager = $serviceManager->get(SessionManager::class);
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserService::class => function ($container) {
                    $table = $container->get(UserTable::class);
                    return new UserService($table, new EmailAddress());
                },
                UserTable::class => function ($container) {
                    $tableGateway = $container->get(UserTableGateway::class);
                    return new UserTable($tableGateway);
                },
                UserTableGateway::class => function ($container) {
                    $adapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User);
                    return new TableGateway('user', $adapter, null, $resultSetPrototype->buffer());
                },
                AuthService::class => function ($container) {
                    return new AuthService($container->get(AuthenticationService::class), $container->get(SessionManager::class));
                },
                AuthenticationService::class => function ($container) {
                    $sessionManager = $container->get(SessionManager::class);
                    $authStorage = new SessionStorage('Laminas_Auth', 'session', $sessionManager);
                    $authAdapter = $container->get(AuthAdapter::class);
                    return new AuthenticationService($authStorage, $authAdapter);
                },
                AuthAdapter::class => function ($container) {
                    return new AuthAdapter($container->get(UserService::class));
                }
            ]
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                UserController::class => function ($container) {
                    return new UserController($container->get(UserService::class), new Container());
                },
                AuthController::class => function ($container) {
                    return new AuthController($container->get(UserService::class), $container->get(AuthService::class), new Container());
                }
            ]
        ];
    }
}
