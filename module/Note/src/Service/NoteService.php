<?php

namespace Note\Service;

use Laminas\Validator\EmailAddress;
use Note\Model\NoteTable;
use User\Model\UserTable;

class NoteService
{
    protected $table;
    protected $emailValidator;
    protected $userTable;

    public function __construct(NoteTable $table, UserTable $userTable, EmailAddress $emailValidator)
    {
        $this->table = $table;
        $this->userTable = $userTable;
        $this->emailValidator = $emailValidator;
    }

    public function saveNote($note)
    {
        $data = [
            'id' => $note->getId(),
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
        ];

        foreach ($data as $key => $value) {
            if ($value == null) {
                throw new \Throwable("There's null values");
            }
        }
        if (strlen($data['title']) > 50) {
            throw new \Throwable("The title has a maximum size of 15 characters");
        }

        $this->table->saveNote($data);
    }

    public function getNote($id)
    {
        try {
            $this->table->getNote($id);
        } catch (\Throwable $th) {
            echo 'A error happened when we tried to get your note  : ' . $th;
        }
    }

    public function getNotes($user_id)
    {
        try {
            return $this->table->getNotes($user_id);
        } catch (\Throwable $th) {
            echo 'A error happened when we tried to list your notes  : ' . $th;
        }
    }

    public function deleteNote($id)
    {
        try {
            $this->table->deleteNote($id);
        } catch (\Throwable $th) {
            echo 'A error happened when we tried to delete your note: ' . $th;
        }
    }

    public function updateNote($note)
    {
        $data = [
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
        ];

        foreach ($data as $key => $value) {
            if ($value == null) {
                throw new \Throwable("There's null values");
            }
        }
        if (strlen($data['title']) > 50) {
            throw new \Throwable("The title has a maximum size of 50 characters");
        }

        $this->table->updateNote($data, $note->getId());
    }
}
