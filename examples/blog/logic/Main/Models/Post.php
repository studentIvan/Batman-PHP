<?php
namespace Main\Models;

class Post extends \Framework\Common\Model
{
    public $title, $content, $tags, $created_at;

    public function __construct($title, $content, $tags)
    {
        $this->title = $title;
        $this->content = $content;
        $this->tags = $tags;
        $this->created_at = $this->getDateTimeNow();
    }
}