<?php


namespace App\Model;

use Nette;
use Nette\Utils\ArrayHash;

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

    public function insertIntoReservation (ArrayHash $values) {
        if ($this->freeDate($values)) {
            $this->database->table('reservationinfo')->insert([
                'name' => $values->name,
                'email' => $values->email,
                'datetime' => $values->datetime,
                'place' => $values->place,
                'approved' => $values->approved,
                'created' => $values->created,
            ]);
            }
        }

    public function freeDate (ArrayHash $values) {
        $dateDiffMinus = date('yy-m-d H:i:s', strtotime($values->datetime .'+4 hours'));
        $dateDiffPlus = date('yy-m-d H:i:s', strtotime($values->datetime .'-4 hours'));
        $result = $this->database->query('SELECT * FROM reservationinfo WHERE', [
            $this->database::literal('datetime > ? AND datetime < ?', $dateDiffPlus, $dateDiffMinus),
        ])->fetch();
        if (empty($result)) {
            return true;
        } else return false;
    }
}