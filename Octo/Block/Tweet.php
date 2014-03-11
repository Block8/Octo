<?php

namespace Octo\Block;

use Octo\Block;
use Octo\Store;

class Tweet extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Latest Tweet', 'editor' => false, 'js' => []];
    }

    public function renderNow()
    {
        $this->renderTweets();
    }

    public function renderTweets()
    {
        $tweets = Store::get('Tweet')->getAllForScope('user');
        $this->view->tweets = $tweets;
    }
}
