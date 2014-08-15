<?php

namespace Octo\Pages\Block;

use Octo\Block;

class PageImage extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Page Image', 'editor' => false, 'js' => []];
    }

    public function renderNow()
    {
        $this->view->width = 800;
        $this->view->height = 'auto';
        $this->view->format = 'jpeg';

        if (isset($this->templateParams['width'])) {
            $this->view->width = $this->templateParams['width'];
        }

        if (isset($this->templateParams['height'])) {
            $this->view->height = $this->templateParams['height'];
        }

        if (isset($this->templateParams['format'])) {
            $this->view->format = $this->templateParams['format'];
        }

        $pageImage = $this->pageVersion->getImageId();
        if (!empty($pageImage)) {
            $this->view->image = $pageImage;
        }
    }
}
