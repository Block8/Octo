<?php

namespace Octo\Pages\Block;

use Octo\Block;

class PageImage extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Text', 'editor' => false, 'js' => []];
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

        $pageImage = $this->pageVersion->getImageId();
        if (!empty($pageImage)) {
            $this->view->image = $pageImage;
        }
    }
}
