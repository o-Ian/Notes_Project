<?php

namespace User\Form;

use Laminas\Form\Form;

class LoginForm extends Form
{
    function __construct($name = null)
    {
        parent::__construct('user');
        $this->setAttribute('method', 'POST');

        $this->add([
            'name' => 'username',
            'type' => 'text',
            'options' => [
                'label' => 'Username:'
            ],
            'attributes' => [
                'class' => 'form-control'
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'email',
            'options' => [
                'label' => 'E-mail:'
            ],
            'attributes' => [
                'class' => 'form-control'
            ]
        ]);

        $this->add([
            'type' => 'checkbox',
            'name' => 'remember_me',
            'options' => [
                'use_hidden_element' => true,
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Submit',
                'id' => 'buttonRegister',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}
