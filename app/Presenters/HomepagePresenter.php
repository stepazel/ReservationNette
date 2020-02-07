<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\ReservationManager;
use Nette\Application\UI;
use Nette\Utils\ArrayHash;



final class HomepagePresenter extends BasePresenter
{

    /** @var ReservationManager */
    private $reservationManager;

    public function __construct(ReservationManager $reservationManager) {
        $this->reservationManager = $reservationManager;
    }

    protected function createComponentReservationForm (): UI\Form {
        $form = new UI\Form;

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'Email:')
            ->setRequired();

        $form->addText('datetime', 'Čas konání:')
            ->setRequired()
            ->setHtmlAttribute('type', 'datetime-local');

        $form->addText('place', 'Místo konání:')
            ->setRequired();

        $form->addSubmit('submit');

        $form->onSuccess[] = [$this, 'reservationFormSucceeded'];

        return $form;
    }

    public function reservationFormSucceeded (UI\Form $form, ArrayHash $values) {
        if ($this->reservationManager->freeDate($values)) {
            $this->reservationManager->insertIntoReservation($values);
            $this->flashMessage('nazdar', 'success');
        } else {
            $this->flashMessage('kokot', 'error');
        }
    }
}
