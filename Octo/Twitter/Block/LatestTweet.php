<?php

namespace Octo\Twitter\Block;

use Octo\Block;
use Octo\Store;

class LatestTweet extends Block
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
        $this->view->tweet =  Store::get('Tweet')->getLatestTweet();
    }
}
