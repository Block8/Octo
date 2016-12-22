<?php

use Phinx\Migration\AbstractMigration;

class ContactAddressJson extends AbstractMigration
{
    public function change()
    {
        $this->query('UPDATE contact SET address = \'[]\' WHERE address IS NULL;');

        $contact = $this->table('contact');
        $contact->changeColumn('address', 'json', ['null' => false]);
        $contact->save();
    }
}
