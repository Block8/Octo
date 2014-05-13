<?php

namespace Octo\Http;

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
