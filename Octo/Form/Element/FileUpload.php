<?php

namespace Octo\Form\Element;

use b8\Config;
use b8\View;
use b8\Form\Element\Upload;

class FileUpload extends Upload
{
    protected $uploadedName;
    protected $uploadedPath;

    public function render($viewFile = 'Text')
    {
        return parent::render($viewFile);
    }

    protected function onPreRender(&$view)
    {
        parent::onPreRender($view);
        $view->type = 'file';
    }

    /**
     * Validate the upload
     *
     * @return bool
     */
    public function validate(&$errors = [])
    {
        if (!$this->checkValue($errors)) {
            return false;
        }

        $validator = $this->getValidator();
        $this->callValidator($validator, $errors);

        if ($this->customError) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the correct value has been entered
     *
     * @return bool
     */
    protected function checkValue(&$errors = [])
    {
        if ($this->getRequired() && !array_key_exists($this->getName(), $_FILES)) {
            $errors[] = $this->getLabel() . ' is required.';
            $this->error = $this->getLabel() . ' is required.';
            return false;
        }

        return true;
    }

    public function getValue()
    {
        if (array_key_exists($this->getName(), $_FILES)) {
            $extension = substr($_FILES[$this->getName()]['name'], strrpos($_FILES[$this->getName()]['name'], '.'));
            $filename = '/uploads/' . md5(file_get_contents($_FILES[$this->getName()]['tmp_name'])) . $extension;
            $path = APP_PATH . 'public' . $filename;
            move_uploaded_file($_FILES[$this->getName()]['tmp_name'], $path);

            $url = Config::getInstance()->get('site.url') . $filename;
            $this->uploadedName = $_FILES[$this->getName()]['name'];
            $this->uploadedPath = $path;

            return $url;
        }

        return null;
    }

    /**
     * Call the custom validator
     *
     * @param $validator
     * @return bool
     */
    protected function callValidator($validator, &$errors = [])
    {
        if (is_callable($validator)) {
            try {
                call_user_func_array($validator, array($this->value));
            } catch (\Exception $ex) {
                $errors[] = $ex->getMessage();
                $this->error = $ex->getMessage();
                return false;
            }
        }
    }

    public function getUploadedName()
    {
        return $this->uploadedName;
    }

    public function getUploadedPath()
    {
        return $this->uploadedPath;
    }
}
