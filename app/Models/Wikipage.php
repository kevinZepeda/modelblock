<?php  namespace App\Models;

class Wikipage {

    protected $title;
    protected $content;
    public $id;

    public function __construct($title, $content, $id)
    {
        $this->title = $title;
        $this->content = $content;
        $this->id = $id;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setTitle($title){
        $this->title = $title;
    }

}