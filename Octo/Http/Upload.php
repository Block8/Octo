<?php

namespace Octo\Http;

class Upload
{
    protected $files = [];

    public function __construct($postKey)
    {
        if (!array_key_exists($postKey, $_FILES)) {
            throw new \Exception('No file upload with key: ' . $postKey);
        }

        if (!isset($_FILES[$postKey]['name'][0]) || $_FILES[$postKey]['name'][0] == '') {
            // No file submitted
            return null;
        }

        if (is_array($_FILES[$postKey]['name'])) {
            // Uploading multiple
            $this->files = [];
            $numFiles = count($_FILES[$postKey]['name']);

            for ($i = 0; $i < $numFiles; $i++) {
                $file = [
                  'name' => $_FILES[$postKey]['name'][$i],
                  'type' => $_FILES[$postKey]['type'][$i],
                  'tmp_name' => $_FILES[$postKey]['tmp_name'][$i],
                  'error' => $_FILES[$postKey]['error'][$i],
                  'size' => $_FILES[$postKey]['size'][$i]
                ];
                $this->files[] = new SingleFileUpload($postKey, [$postKey => $file]);
            }
        } else {
            $this->files[] = new SingleFileUpload($postKey, $_FILES);
        }
    }

    public function getFiles()
    {
        return $this->files;
    }
}

class SingleFileUpload extends \b8\Http\Upload
{

    public function __construct($postKey, $filesArray = null)
    {
        if ($filesArray == null) {
            $filesArray = $_FILES;
        }

        $this->postKey = $postKey;

        if (!array_key_exists($postKey, $_FILES)) {
            throw new \Exception('No file upload with key: ' . $postKey);
        }

        $this->upload = $filesArray[$postKey];

        $this->handleUploadErrors($this->upload['error']);
        $this->setFileInfo();
    }

}
