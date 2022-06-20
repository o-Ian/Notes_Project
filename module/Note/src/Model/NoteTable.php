<?php

namespace Note\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class NoteTable
{
    protected $tableGateway;
    protected $_name = 'note';
    protected $_referenceMap = [
        'User' => [
            'columns' => ['user_id'],
            'refTableClass' => 'UserTable',
            'refColumns' => ['id'],
        ]
    ];

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveNote($data)
    {
        return $this->tableGateway->insert($data);
    }

    public function getNote($id)
    {
        $data = $this->tableGateway->select(['id' => $id]);
        return $data->current;
    }

    public function getNotes($user_id)
    {
        $data = $this->tableGateway->select(['user_id' => $user_id]);
        return $data;
    }

    public function deleteNote($id)
    {
        $this->tableGateway->delete(['id' => $id]);
    }

    public function updateNote($data, $id)
    {
        $this->tableGateway->update($data, ['id' => $id]);
    }
}
