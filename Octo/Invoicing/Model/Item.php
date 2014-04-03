<?php

/**
 * Item model for table: item */

namespace Octo\Invoicing\Model;

use Octo;
use Octo\Store;

/**
 * Item Model
 */
class Item extends Octo\Model
{
    use Base\ItemBase;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);

        $this->getters['main_image'] = 'getMainImage';
        $this->getters['images'] = 'getImages';
    }

    /**
     * Get the first image for this file
     *
     * @return string
     * @author James Inman
     */
    public function getMainImage()
    {
        $itemFileStore = Store::get('ItemFile');
        $images = $itemFileStore->getByItemId($this->getId(),
            ['order' =>
                [
                    ['position', 'ASC'],
                    ['id', 'ASC']
                ]
            ]);

        if (isset($images[0])) {
            $file = $images[0]->getFile();
            return $file;
        }
    }

    public function getImages()
    {
        $itemFileStore = Store::get('ItemFile');
        $images = $itemFileStore->getByItemId($this->getId(),
            ['order' =>
                [
                    ['position', 'ASC'],
                    ['id', 'ASC']
                ]
            ]);
        $files = [];

        foreach ($images as $image) {
            $files[] = $image->getFile();
        }

        return $files;
    }
}
