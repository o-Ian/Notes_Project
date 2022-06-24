<?php

namespace Note\Service;

use Laminas\Authentication\Result;
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
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'user_id' => $note->getUser_Id()
        ];
        $errors = [];

        if (strlen($data['title']) > 50) {
            array_push($errors, new Result(Result::FAILURE, null, ["The title has a maximum size of 50 characters."]));
        }
        if (strlen($data['content']) > 10000) {
            array_push($errors, new Result(Result::FAILURE, null, ["The content has a maximum size of 10000 characters."]));
        }

        foreach ($data as $key => $value) {
            if ($value == null) {
                array_push($errors, new Result(Result::FAILURE, null, ["The {$key} has to contain a {$key}."]));
            }
        }

        if ($errors) {
            return $errors;
        }

        $this->table->saveNote($data);
        return new Result(Result::SUCCESS, $note, ["The note has been created"]);
    }

    public function getNote($id)
    {
        try {
            $data = $this->table->getNote($id);
            return $data;
        } catch (\Throwable $th) {
            throw new \Exception('A error happened when we tried to get your note  : ' . $th);
        }
    }

    public function getNotes($user_id)
    {
        try {
            return $this->table->getNotes($user_id);
        } catch (\Throwable $th) {
            throw new \Exception('A error happened when we tried to list your notes  : ' . $th);
        }
    }

    public function deleteNote($id)
    {
        try {
            $this->table->deleteNote($id);
            return new Result(Result::SUCCESS, $id, ['You deleted the post successfuly']);
        } catch (\Throwable $th) {
            return new Result(Result::FAILURE, null, ['A error happened when we tried to delete your note: ']);
        }
    }

    public function updateNote($note)
    {
        $data = [
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
        ];
        $errors = [];

        if (strlen($data['title']) > 50) {
            array_push($errors, new Result(Result::FAILURE, null, ["The title has a maximum size of 50 characters"]));
        }

        foreach ($data as $key => $value) {
            if ($value == null) {
                array_push($errors, new Result(Result::FAILURE, null, ["The {$key} has to contain a {$key}."]));
            }
        }

        if ($errors) {
            return $errors;
        }

        try {
            $this->table->updateNote($data, $note->getId());
            return new Result(Result::SUCCESS, $note, ["The note has been edited!"]);
        } catch (\Throwable $th) {
            return new Result(Result::FAILURE, null, ["An unexpected error happened when we tried to update your note"]);
        }
    }
}
