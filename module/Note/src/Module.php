<?php

declare(strict_types=1);

namespace Note;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Session\Container;
use Laminas\Validator\EmailAddress;
use PhpParser\Node\Expr\FuncCall;
use Note\Controller\IndexController;
use Note\Controller\NoteController;
use Note\Model\Note;
use Note\Model\NoteTable;
use Note\Service\NoteService;
use User\Model\UserTable;
use User\Service\UserService;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                NoteService::class => function ($container) {
                    $table = $container->get(NoteTable::class);
                    $userTable = $container->get(UserTable::class);
                    return new NoteService($table, $userTable, new EmailAddress);
                },
                NoteTable::class => function ($container) {
                    $tableGateway = $container->get(NoteTableGateway::class);
                    return new NoteTable($tableGateway);
                },
                NoteTableGateway::class => function ($container) {
                    $adapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Note);
                    return new TableGateway('note', $adapter, null, $resultSetPrototype->buffer());
                }
            ]
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                NoteController::class => function ($container) {
                    return new NoteController($container->get(NoteService::class), new Container());
                }
            ]
        ];
    }
}
