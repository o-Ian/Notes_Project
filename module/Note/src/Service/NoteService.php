<?php

namespace Note\Service;

use Laminas\Validator\EmailAddress;
use Note\Model\NoteTable;

class NoteService
{
    protected $table;
    protected $emailValidator;

    public function __construct(NoteTable $table, EmailAddress $emailValidator)
    {
        $this->table = $table;
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

    public function getNote($note)
    {
        try {
            $this->table->getNote($note->getId());
        } catch (\Throwable $th) {
            echo 'A error happened when we tried to get your note  : ' . $th;
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
