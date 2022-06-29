<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;
use User\Form\RegisterForm;
use User\Model\User;
use User\Service\UserService;

class UserController extends AbstractActionController
{
    protected $userService;
    protected $container;

    public function __construct(UserService $userService, Container $container)
    {
        $this->container = $container;
        $this->userService = $userService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }
    public function registerAction()
    {
        $form = new RegisterForm();
        $request = $this->getRequest();
        if (!$request->isPost()) {
            if ($this->flashMessenger()->hasErrorMessages()) {
                return ['form' => $form->bind($this->container->values)];
            }
            return ['form' => $form];
        }

        $user = new User;
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid.');
            return $this->redirect()->toRoute('user/register');
        }

        $user->exchangeArray($form->getData());
        $newForm = new RegisterForm();
        $dbOperation = $this->userService->saveUser($user);
        $operation = $this->db_serviceOperation_form($dbOperation);
        if (!$operation) {
            $this->container->values = $request->getPost();
            return $this->redirect()->toRoute('user/register');
        }
        return $this->redirect()->toRoute('user/login');
    }

    public function profileAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }
        $user = $this->identity();
        return ['user' => $user];
    }
}
