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

        $this->isLogged();

        $form = new LoginForm();
        if (!$this->getRequest()->isPost()) {
            return ['form' => $form];
        }

        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid');
            return $this->redirect()->toRoute('user/login');
        }

        $data = $form->getData();

        $result = $this->authService->login($data['email'], $data['username'], $data['remember_me']);

        $this->db_serviceOperation($result, 'user/login', 'notes/list');
    }

    public function logoutAction()
    {
        $this->authService->logout();

        if (!$this->identity()) {
            $this->flashMessenger()->addSuccessMessage('You have been logged out successfully');
            return $this->redirect()->toRoute('home');
        }
        $this->flashMessenger()->addErrorMessage('A unexpected error happened when we tried to log you out');
        return $this->redirect()->toRoute('home');
    }
}
