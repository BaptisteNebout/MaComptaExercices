<?php


namespace App\DataObject;

class EcritureDTO
{
    public $uuid;
    public $compte_uuid;
    public $label;
    public $date;
    public $type;
    public $amount;
    public $created_at;
    public $updated_at;

    public function __construct($uuid, $compte_uuid, $label, $date, $type, $amount, $created_at, $updated_at)
    {
        $this->uuid = $uuid;
        $this->compte_uuid = $compte_uuid;
        $this->label = $label;
        $this->date = $date;
        $this->type = $type;
        $this->amount = $amount;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
}