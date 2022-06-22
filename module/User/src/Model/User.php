<?php

namespace User\Model;

/* use Laminas\InputFilter\InputFilter;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface; */

class User /* implements InputFilterAwareInterface */
{
    protected $id;
    protected $username;
    protected $firstName;
    protected $lastName;
    protected $email;

    public function exchangeArray($data)
    {
        $this->setId(trim($data['id']));
        $this->setUsername(trim($data['username']));
        $this->setFirstName(trim($data['firstName']));
        $this->setLastName(trim($data['lastName']));
        $this->setEmail(trim($data['email']));
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail()
        ];
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

    /**
     * Get the value of firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @return  self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
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
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
