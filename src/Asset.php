<?php
header('Content-Type: application/json; charset=utf-8');
class Asset {
    public $id;
    public $name;
    public $location;
    public $place;
    public $responsible_person;
 
    public function __construct($id, $name, $location, $place, $responsible_person) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->place = $place;
        $this->responsible_person = $responsible_person;
    }
} 
?>
