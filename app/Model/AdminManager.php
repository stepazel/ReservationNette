<?php


namespace App\Model;
use Nette;


class AdminManager {

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function getReservations () {
        return $this->database->table('reservationinfo');
    }

    public function updateApproved (int $id, int $value) {
        $this->database->table('reservationinfo')
            ->where('id', $id)
            ->update([
                'approved' => $value
            ]);
    }
}