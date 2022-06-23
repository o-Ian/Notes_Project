<?php

declare(strict_types=1);

namespace User\Controller;

use Laminas\Authentication\Result;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use User\Form\RegisterForm;
use User\Model\User;
use User\Service\UserService;

class UserController extends AbstractActionController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
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
            return new ViewModel(['form' => $form]);
        }

        $user = new User;
        $form->setData($request->getPost());
        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid.');
            return $this->redirect()->toRoute('user/register');
        }

        $user->exchangeArray($form->getData());
        $dbOperation = $this->userService->saveUser($user);
        $this->db_serviceOperation($dbOperation, 'user/register', 'user/login');
    }

    public function profileAction()
    {
        $this->isLogged();
        $user = $this->identity();
        return ['user' => $user];
    }
}
