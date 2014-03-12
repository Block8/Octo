<?php

/**
 * Article store for table: article
 */

namespace Octo\Store;

use b8\Database;
use Octo\Store\Base\ArticleStoreBase;
use Octo\Model\Article;

/**
 * Article Store
 * @uses Octo\Store\Base\ArticleStoreBase
 */
class ArticleStore extends ArticleStoreBase
{
    /**
     * Return all articles for a particular category scope
     *
     * @param string $scope
     * @param string $order
     * @return array
     */
    public function getAllForCategoryScope($scope, $order = 'name ASC')
    {
        $query = 'SELECT article.* FROM article
                    LEFT JOIN category ON category.id = article.category_id
                    WHERE category.scope = :scope
                    ORDER BY ' . $order;
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Article($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn);
        } else {
            return array('items' => array());
        }
    }

    /**
     * Get the most recent articles for a category
     *
     * @param $categoryId Category to retrieve articles for
     * @param int $limit Number of articles to retrieve
     * @return array
     */
    public function getRecent($categoryId = null, $limit = 10)
    {
        $where = '';
        if (!is_null($categoryId)) {
            $where = ' WHERE category_id = :catId ';
        }

        $query = 'SELECT COUNT(*) AS cnt FROM article '.$where;
        $stmt = Database::getConnection('read')->prepare($query);

        if (!is_null($categoryId)) {
            $stmt->bindValue(':catId', (int)$categoryId, Database::PARAM_INT);
        }

        $stmt->execute();
        $res = $stmt->fetch(Database::FETCH_ASSOC);
        $count = $res['cnt'];


        $query = 'SELECT article.* FROM article '.$where.' ORDER BY id DESC LIMIT :limit';
        $stmt = Database::getConnection('read')->prepare($query);

        if (!is_null($categoryId)) {
            $stmt->bindValue(':catId', (int)$categoryId, Database::PARAM_INT);
        }

        $stmt->bindValue(':limit', (int)$limit, Database::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Article($item);
            };
            $rtn = array_map($map, $res);

            return array('items' => $rtn, 'count' => $count);
        }

        return array('items' => array(), 'count' => $count);
    }
}
