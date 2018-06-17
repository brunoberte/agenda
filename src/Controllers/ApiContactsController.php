<?php
declare(strict_types=1);

namespace Agenda\Controllers;

use Agenda\Models\Contact;

class ApiContactsController extends AppController
{
    public function index(): void
    {
        $contact = new Contact();
        $contacts = $contact->findAll();

        $this->renderJson($contacts);
    }

    public function view($id = null): void
    {
        $contact = new Contact();
        if ($id) {
            $contact = $contact->findById($id);
        }

        $this->renderJson($contact);
    }

    public function create(): void
    {
        $contact = new Contact();
        $contact->first_name = $this->server_request->getParsedBody()['first_name'];
        $contact->last_name = $this->server_request->getParsedBody()['last_name'];
        $contact->email = $this->server_request->getParsedBody()['email'];
        $contact->main_number = $this->server_request->getParsedBody()['main_number'];
        $contact->secondary_number = $this->server_request->getParsedBody()['secondary_number'];
        $errors = $contact->validate();
        if (!empty($errors)) {
            $this->renderJson(['errors'  => $errors], 400);
            return;
        }
        $contact->save();
        $this->renderJson([$contact], 201);
    }

    public function update($id = null): void
    {
        $contact = new Contact();
        $contact->id = $id;
        $contact->first_name = $this->server_request->getParsedBody()['first_name'];
        $contact->last_name = $this->server_request->getParsedBody()['last_name'];
        $contact->email = $this->server_request->getParsedBody()['email'];
        $contact->main_number = $this->server_request->getParsedBody()['main_number'];
        $contact->secondary_number = $this->server_request->getParsedBody()['secondary_number'];

        $errors = $contact->validate();
        if (!empty($errors)) {
            $this->renderJson(['errors'  => $errors], 400);
            return;
        }
        $contact->save();
        $this->renderJson([$contact], 200);
    }

    public function delete($id = null): void
    {
        $contact = new Contact();
        $contact = $contact->findById($id);
        $contact->delete();
    }
}
