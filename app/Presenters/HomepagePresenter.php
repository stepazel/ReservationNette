<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\ReservationManager;
use Nette\Forms\Form;


final class HomepagePresenter extends BasePresenter
{

    /** @var ReservationManager */
    private $reservationManager;

    public function __construct(ReservationManager $reservationManager) {
        $this->reservationManager = $reservationManager;
    }

    protected function createComponentReservationForm (): Form {
        $form = new Form;

        $form->addText('name', 'Jméno')
            ->setRequired();

        $form->addEmail('email', 'Email:')
            ->setRequired();

       // $form->addDateTimePicker('datetime', 'Date and time:', 16)
         //   ->setFormat('d/m/Y H:i');

        $form->addText('place', 'Místo konání')
            ->setRequired();

        $form->addSubmit('submit');

        return $form;
    }
}
