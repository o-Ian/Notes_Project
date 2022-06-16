<?php

namespace User\Service;

use Exception;
use Laminas\Authentication\Result;
use Laminas\Validator\EmailAddress;
use User\Controller\IndexController;
use User\Model\User;
use User\Model\UserTable;

class UserService extends IndexController
{
    protected $table;
    protected $emailValidator;

    public function __construct(UserTable $table, EmailAddress $emailValidator)
    {
        $this->table = $table;
        $this->emailValidator = $emailValidator;
    }

    public function saveUser($user)
    {
        $data = [
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail()
        ];

        foreach ($data as $key => $value) {
            if ($value == null) {
                throw new \Exception("There's null values: " . $key);
            }
            if ($key != 'email') {
                if (strlen($value) > 15) {
                    throw new \Exception("The {$key} has a maximum lenght of 15 characters");
                }
            }
            if ($key == 'email') {
                $email = $this->table->verifyEmail($value);
                if ($email->count() > 0) {
                    throw new \Exception('A user with this email already exist');
                }
            }
            if ($key == 'username') {
                $username = $this->table->verifyUsername($value);
                if ($username->count() > 0) {
                    throw new \Exception('A user with this username already exist');
                }
            }
        }
        if (!$this->emailValidator->isValid($data['email'])) {
            throw new \Exception('The given email is not valid!');
        }



        $this->table->saveUser($data);
    }

    public function getUser($id)
    {
        try {
            return $this->table->getUser($id);
        } catch (\Throwable $th) {
            throw new Exception('A error happened when we tried to get your user info: ' . $th);
        }
    }

    public function authUser($username, $email)
    {
        $data = $this->table->authUser($username, $email);

        if ($data->count() != 1) {
            return;
        }
        return $data;
    }

    public function deleteUser($id)
    {
        try {
            $this->table->deleteUser($id);
        } catch (\Throwable $th) {
            throw new Exception('A error happened when we tried to delete your user: ' . $th);
        }
    }

    public function updateUser($user)
    {
        $data = [
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
        ];

        foreach ($data as $key => $value) {
            if ($key != 'email') {
                if (strlen($value) > 15) {
                    throw new \Exception("The {$key} has a maximum lenght of 15 characters");
                }
            }
        }
        if (!$this->emailValidator->isValid($data['email'])) {
            throw new \Exception('The given email is not valid!');
        }

        $this->table->updateUser($data, $user->getId());
    }

    public function getNotes($user)
    {
        try {
            return $this->table->getNotes($user);
        } catch (\Throwable $th) {
            throw new Exception("A error happened when we tried to get your notes: " . $th);
        }
    }
}