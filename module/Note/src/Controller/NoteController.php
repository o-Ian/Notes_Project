<?php

declare(strict_types=1);

namespace Note\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Note\Service\NoteService;
use User\Service\UserService;

class NoteController extends AbstractActionController
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function listAction()
    {
        $notes = $this->noteService->getNotes($this->identity()['id']);
        return ['notes' => $notes];
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage("You don't have a given ID");
            return $this->redirect()->toRoute('notes/list');
        }

        $this->noteService->deleteNote($id);

        $this->flashMessenger()->addSuccessMessage('You deleted the post successfuly!');
        return $this->redirect()->toRoute('notes/list');
    }
}
