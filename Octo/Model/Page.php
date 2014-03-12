<?php

/**
 * Page model for table: page
 */

namespace Octo\Model;

use Octo\Store;
use Octo\Model\Base\PageBase;
use Octo\Utilities\StringUtilities;

/**
 * Page Model
 * @uses Octo\Model\Base\PageBase
 */
class Page extends PageBase
{
    public function __construct($initialData = array())
    {
        parent::__construct($initialData);
        $this->getters['hasChildren'] = 'hasChildren';
    }

    public function hasChildren()
    {
        $store = Store::get('Page');
        $count = $store->getChildrenCount($this);
        return $count ? true : false;
    }


    public function generateId()
    {
        $this->setId(substr(sha1(uniqid('', true)), 0, 5));
        $this->setUri('temporary-' . $this->getId());
    }

    public function generateUri()
    {
        if (is_null($this->getParentId())) {
            $this->setUri('/');
        } else {
            $uri = $this->getParent()->getUri();

            if (substr($uri, -1) != '/') {
                $uri .= '/';
            }

            $uri .= StringUtilities::generateSlug($this->getCurrentVersion()->getShortTitle());

            $this->setUri($uri);
        }
    }
}
