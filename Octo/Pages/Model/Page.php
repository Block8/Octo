<?php

/**
 * Page model for table: page
 */

namespace Octo\Pages\Model;

use b8\Cache;
use Octo;
use Octo\Store;
use Octo\Utilities\StringUtilities;

/**
 * Page Model
 * @uses Octo\Pages\Model\Base\PageBaseBase
 */
class Page extends Octo\Model
{
    use Base\PageBase;

    public function __construct($initialData = array())
    {
        parent::__construct($initialData);
        $this->getters['hasChildren'] = 'hasChildren';
        $this->cache = Cache::getCache(Cache::TYPE_APC);

        $this->getters['isLocked'] = 'getIsLocked';
        $this->getters['latestVersion'] = 'getLatestVersion';
    }

    public function __get($key)
    {
        return $this->getVariable($key);
    }

    public function getVariable($key)
    {
        // Try local variables:
        if (array_key_exists($key, $this->getters)) {
            $getter = $this->getters[$key];
            return $this->{$getter}();
        }

        // Try content variables:
        $fromContent = $this->getCurrentVersion()->getVariable($key);
        if ($fromContent) {
            return $fromContent;
        }

        // Try and get from parent page:
        if ($this->getParentId()) {
            return $this->getParent()->getVariable($key);
        }

        return null;
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

    public function getLatestVersion()
    {
        return Store::get('Page')->getLatestVersion($this);
    }

    public function getIsLocked()
    {
        $latest = $this->getLatestVersion();

        if ($latest->getUserId() == $_SESSION['user_id']) {
            return false;
        }

        if ($latest->getUpdatedDate() > new \DateTime('-1 min')) {
            return true;
        }

        return false;
    }

    public function getIndexableContent()
    {
        $content = $this->getLatestVersion()->getContentItem()->getContent();
        return $content;
    }
}
