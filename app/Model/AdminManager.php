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

    public function getFilterQuery (Nette\Utils\ArrayHash $parameters) {
        $reservations = $this->database->table('reservationinfo');
        if ($parameters['name']) {
            $reservations->where('name LIKE %?%', $parameters['name']);
        }
        if ($parameters['email']) {
            $reservations->where('email LIKE %?%', $parameters['email']);
        }
        if ($parameters['datetimeFrom'] and $parameters['datetimeTo']) {
            $reservations->where('datetime BETWEEN ? AND ?', $parameters['datetimeFrom'], $parameters['datetimeTo']);
        }
        if ($parameters['place']) {
            $reservations->where('place LIKE %?%', $parameters['place']);
        }
        if ($parameters['approved']) {
            $reservations->where('approved', $parameters['approved']);
        }
        if ($parameters['createdFrom'] and $parameters['createdTo']) {
            $reservations->where('created BETWEEN ? AND ?', $parameters['createdFrom'], $parameters['createdTo']);
        }
        return $reservations->getSql();
    }
}