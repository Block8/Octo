<?php

/**
 * Migration store for table: migration
 */

namespace Octo\System\Store;

use b8\Database;
use Octo;

/**
 * Migration Store
 * @uses Octo\System\Store\Base\MigrationStoreBase
 */
class MigrationStore extends Octo\Store
{
    use Base\MigrationStoreBase;

    public function runMigration(array $migration)
    {
        $pdo = Database::getConnection('write');
        $pdo->beginTransaction();

        try {
            $pdo->exec('SET foreign_key_checks = 0');

            foreach ($migration['queries'] as $query) {
                $pdo->exec($query);
            }

            $pdo->exec('SET foreign_key_checks = 1');
            $pdo->commit();
        } catch (\Exception $ex) {
            $pdo->rollBack();
            throw $ex;
        }
    }
}
