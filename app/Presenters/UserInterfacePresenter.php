<?php


namespace App\Presenters;

use App\Model\ReservationManager;

class UserInterfacePresenter extends BasePresenter {

    /** @var ReservationManager */
    private $reservationManager;

    public function __construct(ReservationManager $reservationManager) {
        $this->reservationManager = $reservationManager;
    }

    public function renderReservations (): void {
        $this->template->approvedReservations = $this->reservationManager->getReservations();
    }

    public function handleConfirmAttendance ($reservationId): void {
        $this->reservationManager->newAttendant($reservationId, $this->getUser()->id);
    }

    public function isAttended ($reservationID) {
        return $this->reservationManager->getAttendance($this->getUser()->id, $reservationID);
    }

}