<?php

/**
 * Permission store for table: permission
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;
use Octo\System\Model\User;

/**
 * Permission Store
 * @uses Octo\System\Store\Base\PermissionStoreBase
 */
class PermissionStore extends Octo\Store
{
    use Base\PermissionStoreBase;

    public function updatePermissions($userId, $perms)
    {
        $pdo = Database::getConnection('write');

        $pdo->beginTransaction();

        try {
            $stmt = $pdo->prepare('DELETE FROM permission WHERE user_id = :userId');
            $stmt->bindValue(':userId', $userId);
            $stmt->execute();

            $pdo = Database::getConnection('write');
            $stmt = $pdo->prepare('INSERT INTO permission (user_id, uri, can_access) VALUES (:userId, :uri, :can)');

            foreach ($perms as $uri => $can) {
                $stmt->bindValue(':userId', $userId);
                $stmt->bindValue(':uri', $uri);
                $stmt->bindValue(':can', $can);
                $stmt->execute();
            }

            $pdo->commit();
        } catch (\Exception $ex) {
            $pdo->rollBack();
        }
    }

    public function getPermissionsArray(User $user)
    {
        $pdo = Database::getConnection('read');
        $stmt = $pdo->prepare('SELECT uri, can_access FROM permission WHERE user_id = :userId');
        $stmt->bindValue(':userId', $user->getId());

        if ($stmt->execute()) {
            $perms = $stmt->fetchAll(Database::FETCH_ASSOC);
            $rtn = [];

            foreach ($perms as $perm) {
                $rtn[$perm['uri']] = (bool)$perm['can_access'];
            }

            return $rtn;
        }

        return [];
    }
}
