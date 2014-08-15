<?php
namespace Octo\Media\Controller;

use b8\Image;
use b8\Form;
use Octo\Controller;
use Octo\Store;
use Octo\System\Store\FileStore;
use Octo\System\Store\FileDownloadStore;
use Octo\System\Model\File;
use Octo\System\Model\FileDownload;

class MediaController extends Controller
{

    /**
     * @var FileStore
     */
    protected $fileStore;

    /**
     *
     */
    public function init()
    {
        $this->fileStore = Store::get('File');
    }

    /**
     * @param $fileId
     * @param int $width
     * @param int $height
     */
    public function render($fileId, $width = null, $height = null, $type = 'jpeg')
    {
        $file = $this->fileStore->getById($fileId);

        Image::$sourcePath = APP_PATH . '/public/uploads/';
        $image = new Image($file->getId() . '.' . $file->getExtension());

        list($originalWidth, $originalHeight) = getimagesize($file->getPath());

        if ($width == null) {
            $width = $originalWidth;
        }
        if ($height == null) {
            $height = $originalHeight;
        }

        $output = (string)$image->render($width, $height, $type);

        header('Content-Type: image/'.$type);
        header('Content-Length: ' . strlen($output));
        header('Cache-Control: public');
        header('Pragma: cache');
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (86400*100)));

        die($output);
    }

    /**
     * Return an AJAX list of all images
     *
     * @param $scope
     * @return string JSON
     */
    public function ajax($scope)
    {
        $files = $this->fileStore->getAllForScope($scope);
//        File::$sleepable = array('id', 'url', 'title');
        foreach ($files as &$item) {
            if(file_exists($item->getPath())) {
                $imageData = getimagesize($item->getPath());
                $item = $item->toArray(1);
                $item['width'] = $imageData[0];
                $item['height'] = $imageData[1];
            }
            else {
                $item = $item->toArray(1);
            }
        }
        print json_encode($files);
        exit;
    }

    /**
     * Download a file
     *
     * Download the file with its original filename and content type, and log the download
     *
     * @param $fileId File ID to download
     */
    public function download($fileId)
    {
        $file = $this->fileStore->getById($fileId);
        $download = new FileDownload();
        $download->setFileId($file->getId());
        $download->setDownloaded(new \DateTime);

        $fileDownloadStore = new FileDownloadStore;
        $fileDownloadStore->save($download);

        header('Content-type: ' . $file->getMimeType());
        header('Content-Disposition: attachment; filename="' . $file->getFilename() . '"');
        readfile($file->getPath());
    }
}
