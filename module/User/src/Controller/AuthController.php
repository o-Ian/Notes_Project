<?php

namespace User\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use User\Form\LoginForm;
use User\Form\UserForm;
use User\Service\AuthService;
use User\Service\UserService;

class AuthController extends AbstractActionController
{
    protected $userService;
    protected $authService;

    public function __construct(UserService $userService, AuthService $authService, Container $container)
    {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->container = $container;
    }

    public function loginAction()
    {
        $form = new LoginForm();
        if (!$this->getRequest()->isPost()) {
            if ($this->flashMessenger()->hasErrorMessages()) {
                return ['form' => $form->bind($this->container->values)];
            }
            return ['form' => $form];
        }

        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid');
            return $this->redirect()->toRoute('user/login');
        }

        $data = $form->getData();

        $dbOperation = $this->authService->login($data['email'], $data['username'], $data['remember_me']);
        $operation = $this->db_serviceOperation_form($dbOperation);
        if (!$operation) {
            $this->container->values = $this->getRequest()->getPost();
            return $this->redirect()->toRoute('user/login');
        }
        return $this->redirect()->toRoute('notes/list');
    }

    public function logoutAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }
        $this->authService->logout();

        if (!$this->identity()) {
            $this->flashMessenger()->addSuccessMessage('You have been logged out successfully');
            return $this->redirect()->toRoute('home');
        }
        $this->flashMessenger()->addErrorMessage('A unexpected error happened when we tried to log you out');
        return $this->redirect()->toRoute('home');
    }
}
