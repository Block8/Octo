<?php

/**
 * Article store for table: article
 */

namespace Octo\Store;

use b8\Database;
use b8\Database\Query;
use Octo;
use Octo\Model\Article;

/**
 * Article Store
 * @uses Octo\Store\Base\ArticleStoreBase
 */
class ArticleStore extends Octo\Store
{
    use Base\ArticleStoreBase;

    /**
     * Return all articles for a particular category scope
     *
     * @param string $scope
     * @param string $order
     * @return array
     */
    public function getAllForCategoryScope($scope, $order = 'name', $direction = 'ASC')
    {
        $query = new Query('Octo\Model\Article');
        $query->select('a.*')->from('article', 'a')->join('category', 'c', 'c.id = a.category_id');
        $query->where('c.scope = :scope');
        $query->order($order, $direction);
        $query->bind(':scope', $scope);

        return $query->execute()->fetchAll();
    }

    /**
     * Get the most recent articles for a category
     *
     * @param $categoryId Category to retrieve articles for
     * @param int $limit Number of articles to retrieve
     * @param string $scope Scope of articles to retrieve
     * @return array
     */
    public function getRecent($categoryId = null, $limit = 10, $scope)
    {
        $where = 'WHERE 1';
        if (!is_null($categoryId)) {
            $where .= ' AND category_id = :catId ';
        }
        if (!is_null($scope)) {
            $where .= ' AND c.scope = :scope ';
        }

        $query = 'SELECT COUNT(*) AS cnt FROM article LEFT JOIN category c ON c.id = article.category_id ' . $where;
        $stmt = Database::getConnection('read')->prepare($query);

        if (!is_null($categoryId)) {
            $stmt->bindValue(':catId', (int)$categoryId, Database::PARAM_INT);
        }

        if (!is_null($scope)) {
            $stmt->bindValue(':scope', $scope, Database::PARAM_STR);
        }

        $stmt->execute();
        $res = $stmt->fetch(Database::FETCH_ASSOC);
        $count = $res['cnt'];

        $query = 'SELECT article.* FROM article LEFT JOIN category c ON c.id = article.category_id ' . $where;
        $query .= ' ORDER BY id DESC LIMIT :limit';
        $stmt = Database::getConnection('read')->prepare($query);

        if (!is_null($categoryId)) {
            $stmt->bindValue(':catId', (int)$categoryId, Database::PARAM_INT);
        }

        if (!is_null($scope)) {
            $stmt->bindValue(':scope', $scope, Database::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, Database::PARAM_INT);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Article($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        }


        return [];
    }
}
