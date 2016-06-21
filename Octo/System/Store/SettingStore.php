<?php

/**
 * Setting store for table: setting
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;
use Octo\System\Model\Setting;

/**
 * Setting Store
 * @uses Octo\System\Store\Base\SettingStoreBase
 */
class SettingStore extends Base\SettingStoreBase
{
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
