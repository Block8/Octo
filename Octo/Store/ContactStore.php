<?php

/**
 * Contact store for table: contact
 */

namespace Octo\Store;

use b8\Database;
use Octo;
use Octo\Model\Contact;

/**
 * Contact Store
 * @uses Octo\Store\Base\ContactStoreBase
 */
class ContactStore extends Octo\Store
{
    use Base\ContactStoreBase;

    /**
     * Get the total number of submissions in the system.
     * @param string $useConnection
     * @return int
     */
    public function getTotal($useConnection = 'read')
    {
        $query = 'SELECT COUNT(*) AS cnt FROM contact';
        $stmt = Database::getConnection($useConnection)->prepare($query);

        if ($stmt->execute()) {
            if ($data = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                return $data['cnt'];
            }
        }

        return 0;
    }

    public function findContact(array $details)
    {
        $database = Database::getConnection('read');

        $query = 'SELECT * FROM contact WHERE email = :email LIMIT 1';
        $stmt = $database->prepare($query);
        $stmt->bindValue(':email', $details['email']);

        if ($stmt->execute()) {
            $res = $stmt->fetch(Database::FETCH_ASSOC);
            return new Contact($res);
        }

        $query = 'SELECT * FROM contact
                    WHERE first_name = :first AND last_name = :last AND (phone = :phone OR postcode = :postcode)
                    LIMIT 1';

        $stmt = $database->prepare($query);
        $stmt->bindValue(':first', $details['first_name']);
        $stmt->bindValue(':last', $details['last_name']);
        $stmt->bindValue(':phone', $details['phone']);
        $stmt->bindValue(':postcode', $details['postcode']);

        if ($stmt->execute()) {
            $res = $stmt->fetch(Database::FETCH_ASSOC);
            return new Contact($res);
        }

        return null;
    }
}
