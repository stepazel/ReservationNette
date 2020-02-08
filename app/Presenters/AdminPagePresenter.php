<?php


namespace App\Presenters;

use App\Model\AdminManager;
use Nette;

class AdminPagePresenter extends BasePresenter {

    /** @var AdminManager */
    private $adminManager;

    public function __construct(AdminManager $adminManager) {
        $this->adminManager = $adminManager;
    }

    public function renderTable () {
        $this->template->reservations = $this->adminManager->getReservations();
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