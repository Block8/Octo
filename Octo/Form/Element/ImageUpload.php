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
     * @var string Whether to accept multiple elements
     */
    protected $multiple = '';

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
        $view->multiple = $this->multiple;
    }

    /**
     * @param $url
     */
    public function setImageId($url)
    {
        $this->imageId = $url;
    }

    /**
     * Sets the "multiple" property on the element to true
     */
    public function setMultiple()
    {
        $this->multiple = 'multiple';
    }
}
