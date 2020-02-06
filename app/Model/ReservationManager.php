<?php


namespace App\Model;

use Nette;

class ReservationManager {
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    private $formData;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function getReservations () {
        return $this->database->table('reservationinfo');
    }

    private function setFormData () {
        $this->formData['name'] = $_POST['name'];
        $this->formData['email'] = $_POST['email'];
        $this->formData['datetime'] = $_POST['datetime'];
        $this->formData['place'] = $_POST['place'];
        $this->formData['approved'] = 0;
        $this->formData['created'] = date('Y-m-d', time());
    }

    public function getFormData (): array {
        return $this->formData;
    }

    public function insertReservation () {

    }
}