<?php
declare(strict_types=1);

namespace Agenda\Controllers;

use Agenda\Models\Contact;

class ContactsController extends AppController
{
    public function index(): void
    {
        $contact = new Contact();
        $contacts = $contact->findAll();
        $this->render(
            'Contacts/index.html.twig',
            [
                'contacts' => $contacts,
            ]
        );
    }
}
