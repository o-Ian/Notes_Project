<?php

namespace User\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Stdlib\ArrayUtils;
use User\Service\UserService;

class UserTable
{
    protected $tableGateway;
    protected $_name = 'user';
    protected $_dependentTables = ['NoteTable'];

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function saveUser($data)
    {
        return $this->tableGateway->insert($data);
    }

    public function getUser($username)
    {
        $data = $this->tableGateway->select(['username' => $username]);
        return $data;
    }

    public function authUser($username, $email)
    {
        $data = $this->tableGateway->select(['username' => $username, 'email' => $email]);
        return $data;
    }

    public function verifyEmail($email)
    {
        $data = $this->tableGateway->select(['email' => $email]);
        return $data;
    }

    public function verifyUsername($username)
    {
        $data = $this->tableGateway->select(['username' => $username]);
        return $data;
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => $id]);
    }

    public function updateUser($data, $id)
    {
        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function getNotes($user)
    {
        return $user->findDependentRowset('NoteTable');
    }
}
