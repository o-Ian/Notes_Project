<?php

namespace Note\Form;

use Laminas\Form\Form;

class CreateForm extends Form
{
    function __construct($name = null)
    {
        parent::__construct('note');
        $this->setAttribute('method', 'POST');
        $this->setAttribute('class', 'form-crud');

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'user_id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'title',
            'type' => 'text',
            'options' => [
                'label' => 'Title:'
            ],
            'attributes' => [
                'class' => 'form-control'
            ]
        ]);

        $this->add([
            'name' => 'content',
            'type' => 'textarea',
            'options' => [
                'label' => 'Content:'
            ],
            'attributes' => [
                'class' => 'form-control ',
                'oninput' => 'if(this.scrollHeight > this.offsetHeight) this.rows += 1'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Create note',
                'id' => 'buttonCreate',
                'class' => 'btn btn-primary'
            ]
        ]);
    }
}
