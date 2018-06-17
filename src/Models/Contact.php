<?php

namespace Agenda\Models;

use PDO;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class Contact extends AppModel
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $main_number;
    public $secondary_number;
    public $created;
    public $updated;

    public function findAll()
    {
        $statement = $this->db->prepare('select * from contacts order by first_name');
        $statement->execute();

        $list = [];
        array_map(function ($item) use(&$list) {
            $obj = new Contact();
            foreach ($item as $k => $v) {
                $obj->{$k} = $v;
            }
            $list[] = $obj;

        }, $statement->fetchAll(PDO::FETCH_ASSOC));

        return $list;
    }

    public function save()
    {
        if (empty($this->id)) {
            $this->created = date('Y-m-d H:i:s');
        }
        $this->updated = date('Y-m-d H:i:s');
        if (empty($this->id)) {
            return $this->insert();
        }
        return $this->update();
    }

    public function delete()
    {
        if (empty($this->id)) {
            throw new \Exception('Invalid record');
        }

        $statement = $this->db->prepare('delete from contacts where id = :id');
        $statement->execute([':id' => $this->id]);
    }

    public function findById($id)
    {
        $statement = $this->db->prepare('select * from contacts where id = :id');
        $statement->execute([':id' => $id]);

        $item = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new \Exception('Invalid ID');
        }

        $obj = new Contact();
        foreach ($item as $k => $v) {
            $obj->{$k} = $v;
        }
        return $obj;
    }

    public function validate()
    {
        $validator = v::attribute('first_name', v::stringType()->length(3,100))
            ->attribute('last_name', v::stringType()->length(3,100))
            ->attribute('email', v::email())
            ->attribute('main_number', v::regex('/^\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}$/'))
            ->attribute('secondary_number', v::optional(v::regex('/^\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}$/')));

        try {
            $validator->assert($this);
        } catch(NestedValidationException $exception) {
            return $exception->getMessages();
        }

        return [];
    }

    private function insert()
    {
        $query = '
        INSERT INTO contacts 
        (first_name, last_name, email, main_number, secondary_number, created, updated)
        VALUES
        (:first_name, :last_name, :email, :main_number, :secondary_number, :created, :updated)
        ';

        $statement = $this->db->prepare($query);
        $statement->execute([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'main_number' => $this->main_number,
            'secondary_number' => $this->secondary_number,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $this->id = $this->db->lastInsertId();
        return true;
    }

    private function update()
    {
        $query = '
        UPDATE contacts SET 
        first_name = :first_name,
        last_name = :last_name,
        email = :email,
        main_number = :main_number,
        secondary_number = :secondary_number,
        created = :created,
        updated = :updated
        WHERE id = :id
        ';

        $statement = $this->db->prepare($query);
        $statement->execute([
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'main_number' => $this->main_number,
            'secondary_number' => $this->secondary_number,
            'created' => $this->created,
            'updated' => $this->updated,
        ]);

        $this->id = $this->db->lastInsertId();
        return true;
    }
}
