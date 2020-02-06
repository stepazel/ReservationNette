<?php


namespace App\Model;

use Nette;

class ReservationManager {
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function getReservations () {
        return $this->database->table('reservationinfo');
    }

    public function insertReservation () {
        // TODO code this
    }
}