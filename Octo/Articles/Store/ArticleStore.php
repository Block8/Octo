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
     * @param string $direction
     * @return array
     */
    public function getAllForCategoryScope($scope, $order = 'publish_date', $direction = 'ASC')
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
     * @param string $scope Scope of articles to retrieve
     * @return array
     */
    public function getRecent($categoryId = null, $limit = 10, $scope = null)
    {
        $where = 'WHERE 1';
        if (!is_null($categoryId)) {
            $where .= ' AND category_id = :catId ';
        }
        if (!is_null($scope)) {
            $where .= ' AND c.scope = :scope ';
        }

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

    public function getModelsToIndex()
    {
        $query = 'SELECT * FROM article';
        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Article($item);
            };
            $rtn = array_map($map, $res);

            return $rtn;
        } else {
            return array();
        }
    }

    /**
     * Return news for mailshots from date range
     * @param $startDate
     * @param $endDate
     */
    public function getNewsforMailshot($startDate, $endDate)
    {
        return $this->getDateRange($startDate, $endDate, 'news', true);
    }

    /**
     * Return articles for a particular category scope, date range, for mailshots
     *
     * @param string $startDate
     * @param string $endDate
     * @param string $scope news
     * @param string $use_in_email
     * @return array
     */
    public function getDateRange($startDate, $endDate, $scope = 'news', $useInEmail = false)
    {
        if ($useInEmail) {
            $use_in_email = " AND a.use_in_email = 1 ";
        } else {
            $use_in_email = "";
        }

        $query = new Query('Octo\Articles\Model\Article');
        $query->select('a.*')->from('article', 'a')->join('category', 'c', 'c.id = a.category_id');
        $where = 'c.scope = :scope '.$use_in_email.' AND a.publish_date BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
        $query->where($where);
        $query->order('publish_date', 'DESC');
        $query->bind(':scope', $scope);
        $query->execute();
        $ret = $query->fetchAll(\PDO::FETCH_ASSOC);

        return $ret;
    }

    /**
     * Check if there are any articles in subcategories
     * @param $categories
     * @return array
     */
    public function checkArticlesForSubCategories($categories)
    {
        $query = new Query('Octo\Articles\Model\Article');
        $query->select('a.*')->from('article', 'a');
        $query->where('a.category_id IN ('.$categories.')');
        $query->limit(1);

        return $query->execute()->fetchAll();
    }
}
