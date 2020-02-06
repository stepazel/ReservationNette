<?php


namespace App\Model;

use Nette;

class ReservationManager {
    use Nette\SmartObject;

    /** @var Nette\Database\Context */
    private $database;

    private $name;
    private $email;
    private $datetime;
    private $place;
    private $approved;
    private $created;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function getReservations () {
        return $this->database->table('reservationinfo');
    }

    private function setFormData () {
        $this->name = $_POST['name'];
        $this->email = $_POST['email'];
        $this->datetime = $_POST['datetime'];
        $this->place = $_POST['place'];
        $this->approved = 0;
        $this->created = date('Y-m-d', time());
    }

    public function insertIntoReservation () {
        $this->setFormData();
        if ($this->freeDate()) {
            $this->database->table('reservationinfo')->insert([
                'name' => $this->name,
                'email' => $this->email,
                'datetime' => $this->datetime,
                'place' => $this->place,
                'approved' => $this->approved,
                'created' => $this->created,
            ]);

            }
        }

    public function freeDate () {
        $dateDiffMinus = date('yy-m-d H:i:s', strtotime($this->datetime .'+4 hours'));
        $dateDiffPlus = date('yy-m-d H:i:s', strtotime($this->datetime .'-4 hours'));
        $result = $this->database->query('SELECT * FROM reservationinfo WHERE', [
            $this->database::literal('datetime > ? AND datetime < ?', $dateDiffPlus, $dateDiffMinus),
        ])->fetch();
        if (empty($result)) {
            return true;
        } else return false;
    }
}