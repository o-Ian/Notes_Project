<?php

namespace User\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;

class AuthAdapter implements AdapterInterface
{
    protected $email;
    protected $username;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function authenticate()
    {
        $user = $this->userService->authUser($this->username, $this->email);
        if (!$user) {
            return new Result(Result::FAILURE_IDENTITY_NOT_FOUND,  null, ['User not found']);
        }
        return new Result(Result::SUCCESS, $this->username, ['Successfully logged in']);
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }
}
