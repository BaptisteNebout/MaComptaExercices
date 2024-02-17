<?php

namespace App\DataObject;

class CompteDTO
{
    public $uuid;
    public $login;
    public $password;
    public $name;
    public $created_at;
    public $updated_at;

    public function __construct($uuid, $login, $password, $name, $created_at, $updated_at)
    {
        $this->uuid = $uuid;
        $this->login = $login;
        $this->password = $password;
        $this->name = $name;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}
