<?php

namespace Note\Model;

class Note
{
    protected $id;
    protected $title;
    protected $content;
    protected $user_id;

    public function exchangeArray($data)
    {
        $this->setId(trim($data['id']));
        $this->setTitle(trim($data['title']));
        $this->setContent(trim($data['content']));
        $this->setUser_Id(trim($data['user_id']));
    }

    public function getArrayCopy()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'user_id' => $this->getUser_Id()
        ];
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUser_Id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of userId
     *
     * @return  self
     */
    public function setUser_Id($id)
    {
        $this->user_id = $id;
        return $this;
    }
}
