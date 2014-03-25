<?php

/**
 * Page store for table: page
 */

namespace Octo\Page\Store;

use Octo;
use b8\Database;
use b8\Database\Query;
use Octo\Page\Model\Page;
use Octo\Page\Model\PageVersion;

/**
 * Page Store
 * @uses Octo\Page\Store\Base\PageStoreBase
 */
class PageStore extends Octo\Store
{
    use Base\PageStoreBase;

    /**
     * Get the homepage for the current site.
     * @param string $useConnection
     * @return Page
     */
    public function getHomepage($useConnection = 'read')
    {
        $query = 'SELECT * FROM page WHERE parent_id IS NULL';
        $stmt = Database::getConnection($useConnection)->prepare($query);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new Page($data);
            }
        }

        return null;
    }

    /**
     * Get the total number of pages in the system.
     * @param string $useConnection
     * @return int
     */
    public function getTotal($useConnection = 'read')
    {
        $query = 'SELECT COUNT(*) AS cnt FROM page';
        $stmt = Database::getConnection($useConnection)->prepare($query);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data['cnt'];
            }
        }

        return 0;
    }

    /**
     * Get the number of child pages for a given page.
     * @param Page $page
     * @param string $useConnection
     * @return int
     */
    public function getChildrenCount(Page $page, $useConnection = 'read')
    {
        $query = 'SELECT COUNT(*) AS cnt FROM page WHERE parent_id = :parent';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':parent', $page->getId());

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data['cnt'];
            }
        }

        return 0;
    }

    /**
     * Get the latest version of a given page from the database.
     * @param Page $page
     * @param string $useConnection
     * @return PageVersion
     */
    public function getLatestVersion(Page $page, $useConnection = 'read')
    {
        $query = 'SELECT * FROM page_version WHERE page_id = :pageId ORDER BY version DESC LIMIT 1';
        $stmt = Database::getConnection($useConnection)->prepare($query);
        $stmt->bindValue(':pageId', $page->getId());

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return new PageVersion($data);
            }
        }

        return null;
    }

    public function getParentPageOptions($options = null, $parent = null, $prefix = '')
    {
        if (is_null($options)) {
            $parent = $this->getHomepage();

            if (is_null($parent)) {
                return [0 => 'Create Site Homepage'];
            }

            $options = [$parent->getId() => $parent->getCurrentVersion()->getTitle()];
        }

        $children = $this->getByParentId($parent->getId());
        foreach ($children as $page) {
            $options[$page->getId()] = $prefix  . $page->getCurrentVersion()->getTitle();
            $options = $this->getParentPageOptions($options, $page, '--' . $prefix);
        }

        return $options;
    }

    public function getLatest($limit = 10)
    {
        $query = 'SELECT * FROM `page` LIMIT :limit';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindValue(':limit', $limit, Database::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Page($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function search($query)
    {
        $count = null;

        $query = 'SELECT p.* FROM page p
            LEFT JOIN page_version v ON v.id = p.current_version_id
            WHERE title LIKE \'%'.$query.'%\' OR short_title LIKE \'%'.$query.'%\'';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Page($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return [];
        }
    }

    public function getUriBestMatch($uri)
    {
        $query = new Query($this->getNamespace('Page').'\Model\Page');
        $query->select('*')->from('page')->where(":uri LIKE CONCAT(uri, '%')")->order('LENGTH(uri)', 'DESC')->limit(1);
        $query->bind(':uri', $uri);

        if ($query->execute()) {
            return $query->fetch();
        }

        return null;
    }
}
