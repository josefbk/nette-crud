<?php

namespace App\Presenters;

use App\Model\Projekt;
use Cntrl\IProjektGridFactory;
use Cntrl\IProjektFormFactory;
use Nette;
use Tracy\Debugger;
use Ublaboo\DataGrid\DataGrid;


class HomepagePresenter extends Nette\Application\UI\Presenter
{


    /** @var  id editovaneho projektu */
    private $idProjektu;

    /** @var Projekt @inject */
    public $modelProjekt;

    public function renderEdit($id)
    {
        $this->idProjektu = $id;
        $this->getTemplate()->idProjektu = $id;
    }

    /** @var IProjektFormFactory @inject */
    public $iProjektFormFactory;

    /**
     * Formulář pro vložení a editaci projektu
     * @return \Cntrl\ProjektFormControl
     */
    public function createComponentProjektFormControl()
    {
        return $this->iProjektFormFactory->create($this->idProjektu, function () {
            $this->getPresenter()->flashMessage("Uloženo", 'success');
            $this->getPresenter()->redirect('this');
        });
    }

    /** @var  IProjektGridFactory @inject */
    public $iProjektGridFactory;

    public function createComponentProjektGridControl(){
        return $this->iProjektGridFactory->create();
    }
}
