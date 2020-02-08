<?php


namespace App\Presenters;

use App\Model\AdminManager;
use Nette;

class AdminPagePresenter extends BasePresenter {

    /** @var AdminManager */
    private $adminManager;

    const itemsPerPage = 10;

    public function __construct(AdminManager $adminManager) {
        $this->adminManager = $adminManager;
    }

    public function renderDefault (int $page = 1) {

        $reservationsCount = $this->adminManager->getReservationsCount();

        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($reservationsCount);
        $paginator->setItemsPerPage(self::itemsPerPage);
        $paginator->setPage($page);

        $reservations = $this->adminManager->getReservations($paginator->getLength(), $paginator->getOffset());

        $this->template->reservations = $reservations;
        $this->template->paginator = $paginator;
        $this->template->x = $reservationsCount;
    }

    public function handleApprovedChange ($id, $value) {
        $this->adminManager->updateApproved($id, $value);
        if ($id === 1) {
            $this->flashMessage('Termín byl potvrzen.');
            $this->redirect('this');
        } else {
            $this->flashMessage('Termín byl zamítnut.');
            $this->redirect('this');
        }
    }
}