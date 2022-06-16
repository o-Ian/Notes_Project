<?php

declare(strict_types=1);

namespace Note;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Validator\EmailAddress;
use PhpParser\Node\Expr\FuncCall;
use Note\Controller\IndexController;
use Note\Model\Note;
use Note\Model\NoteTable;
use Note\Service\NoteService;



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
                    return new NoteService($table, new EmailAddress);
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
                IndexController::class => function ($container) {
                    return new IndexController($container->get(NoteService::class));
                }
            ]
        ];
    }
}
