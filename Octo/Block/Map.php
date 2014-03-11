<?php

namespace Octo\Block;

use Octo\Template;
use Octo\Block;
use Octo\Model\File;

class Map extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Map', 'editor' => true, 'js' => ['/assets/backoffice/js/block/map.js']];
    }

    public function renderNow()
    {
        if (array_key_exists('code', $this->content)) {
            $code = $this->content['code'];
            $matches = [];

            if (preg_match('/src="([^"]+)"/', $code, $matches)) {
                $this->view->link = $matches[1];
            }
        }
    }
}
