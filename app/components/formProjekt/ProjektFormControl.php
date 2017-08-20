<?php

namespace Cntrl;

use App\Forms\FormFactory;
use App\Model\Projekt;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Database\Table\ActiveRow;
use Nette\Utils\DateTime;
use Tracy\Debugger;

class ProjektFormControl extends Control
{
    private $formFactory;
    private $modelProjekt;
    private $projektRow;
    private $onSuccess;

    public function __construct($idProjektu, callable $onSuccess, FormFactory $formFactory, Projekt $modelProjekt)
    {
        $this->formFactory = $formFactory;
        $this->modelProjekt = $modelProjekt;
        $this->projektRow = $modelProjekt->getById($idProjektu);
        $this->onSuccess = $onSuccess;
    }


    public function render()
    {
        //pokud jde o editaci neexistujiciho projektu, vypsat info
        if (!$this->projektRow instanceof ActiveRow && !empty($this->idProjektu)) {
            $this->getTemplate()->setFile(__DIR__ . DIRECTORY_SEPARATOR . "errProjektFormControl.latte");
            $this->getTemplate()->render();
            return;
        }

        $this->getTemplate()->setFile(__DIR__ . DIRECTORY_SEPARATOR . "ProjektFormControl.latte");
        $this->getTemplate()->render();
    }


    public function createComponentProjektForm()
    {
        $form = $this->formFactory->create();
        $form->addHidden(Projekt::COLUMN_ID);
        $form->addText(Projekt::COLUMN_NAZEV_PROJEKTU, "Název projektu")
            ->setRequired("Je potřeba vyplnit název projektu")
            ->addRule(Form::MAX_LENGTH, "Maximální počet znaků je 300", 300);
        $datumInput = $form->addText(Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU, "Datum odevzdání");
        $datumInput->getControlPrototype()->addClass("datepicker");
        $datumInput->setAttribute('placeholder','dd.mm.rrrr');
        $datumInput->setRequired("Je potřeba vyplnit datum odevzdání");
        $form->addSelect(Projekt::COLUMN_TYP_PROJEKTU, "Typ projektu", Projekt::getTypyProjektu())
            ->setRequired("Je potřeba vybrat typ projektu");
        $form->addCheckbox(Projekt::COLUMN_WEBOVY_PROJEKT, "Webový projekt");
        $form->addSubmit('odeslat', 'Uložit');

        if ($this->projektRow instanceof ActiveRow) {
            $form->setDefaults([
                Projekt::COLUMN_ID => $this->projektRow[Projekt::COLUMN_ID],
                Projekt::COLUMN_NAZEV_PROJEKTU => $this->projektRow[Projekt::COLUMN_NAZEV_PROJEKTU],
                Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU => $this->projektRow[Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU]->format('d.m.Y'),
                Projekt::COLUMN_TYP_PROJEKTU => $this->projektRow[Projekt::COLUMN_TYP_PROJEKTU],
                Projekt::COLUMN_WEBOVY_PROJEKT => $this->projektRow[Projekt::COLUMN_WEBOVY_PROJEKT],
            ]);
        }

        $onSuccess = $this->onSuccess;
        $modelProjekt = $this->modelProjekt;
        $form->onSuccess[] = function (Form $form, $values) use ($onSuccess, $modelProjekt) {
            $values[Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU] = DateTime::createFromFormat('d.m.Y', $values[Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU]);
            $row = $modelProjekt->getById($values[Projekt::COLUMN_ID]);
            if ($row instanceof ActiveRow) {
                $modelProjekt->update($values);
            } else {
                $modelProjekt->insert($values);
            }
            $onSuccess();
        };

        return $form;
    }
}

interface IProjektFormFactory
{

    /**
     * @param $idProjektu
     * @param callable $onSuccess
     * @return ProjektFormControl
     */
    public function create($idProjektu, callable $onSuccess);
}