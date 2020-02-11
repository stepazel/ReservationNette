<?php


namespace App\Model;
use Nette;


class AdminManager {

    /** @var Nette\Database\Context */
    private $database;

    private $filters = [];

    public function __construct(Nette\Database\Context $database) {
        $this->database = $database;
    }

    public function setFilters (Nette\Utils\ArrayHash $parameters) {
        $this->filters['name'] = $parameters['name'];
        $this->filters['email'] = $parameters['email'];
        $this->filters['place'] = $parameters['place'];
        $this->filters['approved'] = $parameters['approved'];
        $this->filters['datetimeFrom'] = $parameters['datetimeFrom'];
        $this->filters['datetimeTo'] = $parameters['datetimeTo'];
        $this->filters['createdFrom'] = $parameters['createdFrom'];
        $this->filters['createdTo'] = $parameters['createdTo'];
    }

    public function getFilters () {
        return $this->filters;
    }

    public function getReservations (int $limit, int $offset):Nette\Database\ResultSet {
        return $this->database->query(
            $this->getFilterQuery($this->getFilters()).'
            LIMIT ?
            OFFSET ?',
            $limit, $offset
            );
    }

    public function getReservationsCount (): int {
        return $this->database->table('reservationinfo')->count('*');
    }

    public function updateApproved (int $id, int $value) {
        $this->database->table('reservationinfo')
            ->where('id', $id)
            ->update([
                'approved' => $value
            ]);
    }

    public function getFilterQuery (Array $filters) {
        $reservations = $this->database->table('reservationinfo');
        if ($filters['name']) {
            $reservations->where('name LIKE ?', '%'.$filters['name'].'%');
        }
        if ($filters['email']) {
            $reservations->where('email LIKE ?', '%'.$filters['email'].'%');
        }
        if ($filters['datetimeFrom'] and $filters['datetimeTo']) {
            $reservations->where('datetime BETWEEN ? AND ?', $filters['datetimeFrom'], $filters['datetimeTo']);
        }
        if ($filters['place']) {
            $reservations->where('place LIKE ?', '%'.$filters['place'].'%');
        }
        if ($filters['approved']) {
            $reservations->where('approved', $filters['approved']);
        }
        if ($filters['createdFrom'] and $filters['createdTo']) {
            $reservations->where('created BETWEEN ? AND ?', $filters['createdFrom'], $filters['createdTo']);
        }
    }

}