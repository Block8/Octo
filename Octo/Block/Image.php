<?php

namespace Octo\Block;

use Octo\Block;

class Image extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Text', 'editor' => true, 'js' => ['/assets/backoffice/js/block/image.js']];
    }

    public function renderNow()
    {
        $this->view->width = 800;
        $this->view->height = 'auto';

        if (isset($this->templateParams['width'])) {
            $this->view->width = $this->templateParams['width'];
        }

        if (isset($this->templateParams['height'])) {
            $this->view->height = $this->templateParams['height'];
        }

        if (isset($this->content['image'])) {
            $this->view->image = $this->content['image'];
            return;
        }
    }
}
