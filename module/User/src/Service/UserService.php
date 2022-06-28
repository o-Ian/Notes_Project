<?php

namespace User\Service;

use Exception;
use Laminas\Authentication\Result;
use Laminas\Mvc\Plugin\FlashMessenger\View\Helper\FlashMessenger;
use Laminas\Validator\EmailAddress;
use User\Model\UserTable;

class UserService
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
        $errors = [];
        foreach ($data as $key => $value) {
            if ($value == null) {
                $error = new Result(Result::FAILURE, null, ["There's null values: " . $key]);
                array_push($errors, $error);
            }
            if ($key != 'email') {
                if (strlen($value) > 15) {
                    $error = new Result(Result::FAILURE, null, ["The {$key} has a maximum lenght of 15 characters!"]);
                    array_push($errors, $error);
                }
            }
            if ($key == 'username') {
                $username = $this->table->verifyUsername($value);
                if ($username->count() > 0) {
                    $error = new Result(Result::FAILURE, null, ["A user with this username already exist"]);
                    array_push($errors, $error);
                }
            }
            if ($key == 'email') {
                $email = $this->table->verifyEmail($value);
                if ($email->count() > 0) {
                    $error = new Result(Result::FAILURE, null, ["A user with this email already exist"]);
                    array_push($errors, $error);
                }
            }
            if ($key == 'firstName' || $key == 'lastName') {
                $str = str_replace(' ', '', $value);
                if (!ctype_alpha($str) && !preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/", $str)) {
                    $error = new Result(Result::FAILURE, null, ["The {$key} has numbers and/or special characters in its composition"]);
                    array_push($errors, $error);
                }
            }
        }
        if (!$this->emailValidator->isValid($data['email'])) {
            $error = new Result(Result::FAILURE, null, ["The given email is not valid!"]);
            array_push($errors, $error);
        }
        if ($errors) {
            return $errors;
        }

        try {
            $this->table->saveUser($data);
            return new Result(Result::SUCCESS, null, ['You are registered, now sign in!']);
        } catch (\Throwable $th) {
            return new Result(Result::FAILURE, null, ['A error happened when we trie to save your user']);
        }
    }

    public function getUser($username)
    {
        try {
            return $this->table->getUser($username);
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
            if ($key == 'firstName' || $key == 'lastName') {
                $str = str_replace(' ', '', $value);
                if (!ctype_alpha($str)) {
                    throw new \Exception("The {$key} allows only letters in its composition");
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
