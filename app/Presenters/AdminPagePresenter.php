<?php


namespace App\Presenters;

use App\Model\AdminManager;

use Nette;

class AdminPagePresenter extends BasePresenter {

    /** @var AdminManager */
    private $adminManager;

    /** @persistent */
    public $filters = [];

    const itemsPerPage = 4;

    public function startup() {
        parent::startup();
    }

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
    }

    public function handleApprovedChange ($reservationId, $value) {
        $this->adminManager->updateApproved($reservationId, $value);
        if ($value === '1') {
            $this->flashMessage('Termín byl potvrzen.');
            $this->redirect('this');
        } elseif ($value === '0') {
            $this->flashMessage('Termín byl zamítnut.');
            $this->redirect('this');
        }
    }

    public function createComponentFilterForm () {
        $form = new Nette\Application\UI\Form();

        $form->addText('name', 'Filtrujte pomocí jména');
        $form->addText('email', 'Filtrujte pomocí mailu');
        $form->addText('datetimeFrom', 'Filtrovat pomocí času konání')
            ->setHtmlType('date');
        $form->addText('datetimeTo')
            ->setHtmlType('date');
        $form->addText('place', 'Filtrovat místo konání');
        $form->addText('approved', 'Filtrovat stav potvrzení');
        $form->addText('createdFrom', 'Filtrovat datum vytvoření')
            ->setHtmlType('date');
        $form->addText('createdTo')
            ->setHtmlType('date');
        $form->addSubmit('submit');

        $form->setMethod('get');

        $form->onSuccess[] = [$this, 'filterFormSucceeded'];

        return $form;
    }

    public function filterFormSucceeded (Nette\Application\UI\Form $form, Nette\Utils\ArrayHash $parameters) {


       $this->adminManager->setFilters($parameters);
        bdump($this->filters);

    }

}