<?php

/**
 * Tweet store for table: tweet
 */

namespace Octo\Store;

use b8\Database;
use Octo\Store\Base\TweetStoreBase;
use Octo\Model\Tweet;

/**
 * Tweet Store
 * @uses Octo\Store\Base\TweetStoreBase
 */
class TweetStore extends TweetStoreBase
{
    /**
     * Retrieve a tweet by a Twitter ID for a particular scope
     *
     * @param $twitterId
     * @param $scope
     * @return Tweet|null
     */
    public function getByTwitterIdForScope($twitterId, $scope)
    {
        $query = 'SELECT tweet.* FROM tweet WHERE twitter_id = :twitter_id AND scope = :scope LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':twitter_id', $twitterId);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($res) {
                return new Tweet($res);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Retrieve all tweets for a particular scope
     *
     * @param $scope
     * @param $limit
     * @return Tweet|null
     */
    public function getAllForScope($scope, $limit)
    {
        $query = 'SELECT tweet.* FROM tweet WHERE scope = :scope';
        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Tweet($item);
            };
            $rtn = array_map($map, $res);
            return $rtn;
        } else {
            return null;
        }
    }
}
