<?php

namespace User\Service;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result;
use Laminas\Session\SessionManager;

class AuthService
{
  public $authService;
  protected $sessionManager;

  public function __construct(AuthenticationService $authService, SessionManager $sessionManager)
  {
    $this->authService = $authService;
    $this->sessionManager = $sessionManager;
  }

  public function login($email, $username, $rememberMe)
  {
    $authAdapter = $this->authService->getAdapter();
    $authAdapter->setEmail($email);
    $authAdapter->setUsername($username);
    $result = $this->authService->authenticate();

    if ($result->getCode() == Result::SUCCESS && $rememberMe) {
      $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
    }
    return $result;
  }

  public function logout()
  {
    if (!$this->authService->getIdentity()) {
      throw new \Exception("There's no user logged in the system");
    }

    $this->authService->clearIdentity();
  }
}
