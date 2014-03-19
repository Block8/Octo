<?php

/**
 * Setting store for table: setting
 */

namespace Octo\Store;

use b8\Database;
use Octo;
use Octo\Model\Setting;

/**
 * Setting Store
 * @uses Octo\Store\Base\SettingStoreBase
 */
class SettingStore extends Octo\Store
{
    use Base\SettingStoreBase;

    public function getSettingValue($scope, $key)
    {
        $query = 'SELECT setting.* FROM setting WHERE scope = :scope AND `key` = :key LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':key', $key);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($res) {
                return $res['value'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function getByScopeKey($scope, $key)
    {
        $query = 'SELECT setting.* FROM setting WHERE scope = :scope AND `key` = :key LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);
        $stmt->bindParam(':key', $key);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($res) {
                return new Setting($res);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
