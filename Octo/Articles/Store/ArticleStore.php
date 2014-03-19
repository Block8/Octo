<?php

/**
 * Article store for table: article
 */

namespace Octo\Articles\Store;

use b8\Database;
use b8\Database\Query;
use Octo;
use Octo\Articles\Model\Article;

/**
 * Article Store
 * @uses Octo\Articles\Store\Base\ArticleStoreBase
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
        $query = new Query('Octo\Articles\Model\Article');
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

            return $rtn;
        }


        return [];
    }
}
