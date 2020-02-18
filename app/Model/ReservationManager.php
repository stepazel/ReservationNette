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
            $this->getReservations()->insert([
                'name' => $values->name,
                'email' => $values->email,
                'datetime' => $values->datetime,
                'place' => $values->place,
                'approved' => 0,
                'created' => date('Y-m-d', time()),
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

    public function getApprovedReservations () {
        return $this->database->table('reservationinfo')->where('approved = ?', '1');
    }

    public function newAttendant ($reservationId, $userId): void {
        $this->database->table('concert_attendance')->insert([
            'reservation_id' => $reservationId,
            'user_id' => $userId
        ]);
    }

    public function getAttendance ($userID, $reservationID) {
        $selection = $this->database->table('concert_attendance')
            ->where('user_id = ?', $userID)
            ->where('reservation_id = ?', $reservationID);
        if ($selection->fetch() == null) {
            return false;
        } else {
            return true;
        }
    }

    public function cancelAttendance ($reservationId, $userId): void {
        $this->database->table('concert_attendance')
            ->where('user_id = ?', $userId)
            ->where('reservation_id = ?', $reservationId)
            ->delete();
    }
}