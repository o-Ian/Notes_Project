<?php

declare(strict_types=1);

namespace Note\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Note\Form\CreateForm;
use Note\Form\EditForm;
use Note\Model\Note;
use Note\Service\NoteService;

class NoteController extends AbstractActionController
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function listAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }
        $notes = $this->noteService->getNotes($this->identity()['id']);
        return ['notes' => $notes];
    }

    public function deleteAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }

        $id = (int)$this->params()->fromRoute('id');
        if (!$id) {
            $this->flashMessenger()->addErrorMessage("You don't have a given ID.");
            return $this->redirect()->toRoute('notes/list');
        }

        $note = $this->noteService->getNote($id);
        if (!$note || $note->getUser_Id() != $this->identity()['id']) {
            $this->flashMessenger()->addErrorMessage("You don't have access to this note.");
            return $this->redirect()->toRoute('notes/list');
        }

        $this->db_serviceOperation($this->noteService->deleteNote($id), 'notes/list');
    }

    public function createAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }

        $form = new CreateForm();
        $form->get('user_id')->setValue($this->identity()["id"]);
        if (!$this->getRequest()->isPost()) {
            return ['form' => $form];
        }

        $note = new Note;
        $form->setData($this->getRequest()->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->addErrorMessage('The form is not valid.');
            return $this->redirect()->toRoute('notes/create');
        }

        $note->exchangeArray($form->getData());
        $this->db_serviceOperation($this->noteService->saveNote($note), 'notes/create', 'notes/list');
    }

    public function editAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            $this->flashMessenger()->addErrorMessage("There's no id.");
            return $this->redirect()->toRoute('notes/list');
        }

        $form = new EditForm();
        $form->get('user_id')->setValue($this->identity()["id"]);
        $form->get('id')->setValue($id);

        $note = $this->noteService->getNote($id);

        $this->noteBelongs($note, $this->identity()['id']);

        $form->bind($note);

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form, 'id' => $id];
        }
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            throw new \Exception('The form is not valid.');
        }

        $this->db_serviceOperation($this->noteService->updateNote($note), 'notes/edit', 'notes/view', ['id' => $id]);
    }

    public function viewAction()
    {
        if (!$this->isLogged()) {
            return $this->redirect()->toRoute('home');
        }
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            $this->flashMessenger()->addErrorMessage("There's no id.");
            return $this->redirect()->toRoute('notes/list');
        }

        $note = $this->noteService->getNote($id);

        $this->noteBelongs($note, $this->identity()['id']);

        return ['note' => $note];
    }

    public function noteBelongs($note, $user_Id)
    {
        if (!$note || $note->getUser_Id() != $user_Id) {
            $this->flashMessenger()->addErrorMessage("You don't have access to this note.");
            return $this->redirect()->toRoute('notes/list');
        }
    }
}
