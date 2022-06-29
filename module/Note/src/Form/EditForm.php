<?php

namespace Note\Form;

use Laminas\Form\Form;

class EditForm extends Form
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
            'type' => 'hidden',
            'attributes' => [
                'id' => 'content'
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'value' => 'Save changes',
                'id' => 'buttonEdit',
                'class' => 'btn btn-primary',
                'onclick' => "$('#content').val($('#htmlContent').html())"
            ]
        ]);
    }
}
