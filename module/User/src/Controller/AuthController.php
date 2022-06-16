<?php

namespace User\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use User\Form\LoginForm;
use User\Form\UserForm;
use User\Service\AuthService;
use User\Service\UserService;

class AuthController extends AbstractActionController
{
    protected $userService;
    protected $authService;

    public function __construct(UserService $userService, AuthService $authService,)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function loginAction()
    {
        $isLogged = false;
        if ($this->authService->authService->getIdentity()) {
            $isLogged = $this->authService->authService->getIdentity();
        }
        $form = new LoginForm();
        if (!$this->getRequest()->isPost()) {
            return ['form' => $form, 'isLogged' => $isLogged];
        }

        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid');
            return $this->redirect()->toRoute('auth', ['action' => 'login']);
        }

        $data = $form->getData();

        $result = $this->authService->login($data['email'], $data['username'], $data['remember_me']);

        if ($result->getCode() == Result::SUCCESS) {
            $this->flashMessenger()->addSuccessMessage('You are logged in');
            return $this->redirect()->toRoute('home');
        } else {
            $this->flashMessenger()->addErrorMessage('E-mail and/or username are wrong');
            return $this->redirect()->toRoute('auth', ['action' => 'login']);
        }
    }
}
