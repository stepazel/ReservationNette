<?php


namespace App\Model;
use Nette;


class AdminManager {

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function getReservations (int $limit, int $offset):Nette\Database\ResultSet {
        return $this->database->query('
            SELECT * FROM reservationinfo
            LIMIT ?
            OFFSET ?',
            $limit, $offset
            );
    }

    public function getReservationsCount (): int {
        return $this->database->table('reservationinfo')->count();
    }

    public function updateApproved (int $id, int $value) {
        $this->database->table('reservationinfo')
            ->where('id', $id)
            ->update([
                'approved' => $value
            ]);
    }
}