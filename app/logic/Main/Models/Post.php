<?php
namespace Main\Models;

class Post extends \Framework\Common\Model
{
    public $title, $message, $created_at;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
        $this->created_at = date('Y-m-d H:i:sP');
    }
}