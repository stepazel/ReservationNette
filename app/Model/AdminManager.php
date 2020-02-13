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

    public function setFilters ($parameters) {
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

    public function updateApproved (int $id, int $value) {
        $this->database->table('reservationinfo')
            ->where('id', $id)
            ->update([
                'approved' => $value
            ]);
    }

    public function getAllReservations () {
        return $this->database->table('reservationinfo');
    }

    public function getFilteredReservations (Array $filters) {
        $reservations = $this->database->table('reservationinfo');
            if ($filters['name']) {
                $reservations->where('name LIKE ?', '%' . $filters['name'] . '%');
            }
            if ($filters['email']) {
                $reservations->where('email LIKE ?', '%' . $filters['email'] . '%');
            }
            if ($filters['datetimeFrom'] and $filters['datetimeTo']) {
                $reservations->where('datetime BETWEEN ? AND ?', $filters['datetimeFrom'], $filters['datetimeTo']);
            }
            if ($filters['place']) {
                $reservations->where('place LIKE ?', '%' . $filters['place'] . '%');
            }
            if ($filters['approved'] !== '') {
                $reservations->where('approved', $filters['approved']);
            }
            if ($filters['createdFrom'] and $filters['createdTo']) {
                $reservations->where('created BETWEEN ? AND ?', $filters['createdFrom'], $filters['createdTo']);
            }
        return $reservations;
    }
}