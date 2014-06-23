<?php

namespace Octo\Media\Block;

use Octo\Pages\Block\Text;

class TextImage extends Text
{
    public static function getInfo()
    {
        return ['title' => 'TextImage', 'editor' => true, 'js' => ['/assets/backoffice/js/block/textimage.js']];
    }

    public function renderNow()
    {
        $this->view->width = 512;
        $this->view->height = 'auto';

        if (isset($this->templateParams['width'])) {
            $this->view->width = $this->templateParams['width'];
        }

        if (isset($this->templateParams['height'])) {
            $this->view->height = $this->templateParams['height'];
        }

        if (isset($this->content['image'])) {
            $this->view->image = $this->content['image'];
        }

        if (isset($this->content['link'])) {
            $this->view->link = $this->content['link'];
        }

        if (array_key_exists('content', $this->content)) {
            $content = $this->content['content'];

            // Replace file blocks
            $content = preg_replace_callback('/\<img id\=\"([a-zA-Z0-9]{32})".*>/', [$this, 'replaceFile'], $content);
            $this->view->content = $content;
        }
    }
}
