<?php
namespace Octo\Controller;

use b8\Image;
use b8\Form;
use Octo\Store;
use Octo\Store\FileStore;
use Octo\Store\FileDownloadStore;
use Octo\Model\File;
use Octo\Model\FileDownload;
use Octo\Admin\Controller;
use Octo\Admin\Menu;

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
    public function render($fileId, $width = null, $height = null)
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

        $output = $image->render($width, $height);

        $this->response->setHeader('Content-Type', 'image/jpeg');
        $this->response->setContent($output);
        $this->response->disableLayout();
        $this->response->flush();
        print $this->response->getContent();
        exit;
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
        foreach ($files['items'] as &$item) {
            $imageData = getimagesize($item->getPath());
            $item = $item->toArray(1);
            $item['width'] = $imageData[0];
            $item['height'] = $imageData[1];
        }
        print json_encode($files['items']);
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
        $file = File::getById($fileId);

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
