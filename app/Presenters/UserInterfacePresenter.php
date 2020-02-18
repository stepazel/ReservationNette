<?php


namespace App\Presenters;

use App\Model\ReservationManager;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Presenter;

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
        try {
            if (is_numeric($reservationId)) {
                $this->reservationManager->newAttendant($reservationId, $this->getUser()->id);
            } else {
                throw new ForbiddenRequestException('Akce nemohla být vykonána, zadaný parametr neexistuje.');
            }
        } catch (ForbiddenRequestException $exception) {
            $this->flashMessage($exception->getMessage());
        }
    }

    public function handleCancelAttendance ($reservationId): void {
        $this->reservationManager->cancelAttendance($reservationId, $this->getUser()->id);
    }

    public function isAttended ($reservationID) {
        return $this->reservationManager->getAttendance($this->getUser()->id, $reservationID);
    }

}