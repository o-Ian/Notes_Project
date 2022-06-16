<?php

declare(strict_types=1);

namespace Note\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Note\Service\NoteService;

class IndexController extends AbstractActionController
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }
}
