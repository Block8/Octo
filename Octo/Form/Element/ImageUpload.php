<?php

namespace Octo\Form\Element;

use b8\View;
use b8\Form\Element\Upload;

class ImageUpload extends Upload
{
    /**
     * @var string ID of current image
     */
    protected $imageId;

    /**
     * @param null $viewFile
     * @return string
     */
    public function render($viewFile = null)
    {
        return parent::render('ImageUpload');
    }

    /**
     * @param View $view
     */
    protected function onPreRender(View &$view)
    {
        parent::onPreRender($view);
        $view->type = 'file';
        $view->imageId = $this->imageId;
    }

    /**
     * @param $url
     */
    public function setImageId($url)
    {
        $this->imageId = $url;
    }
}
