<?php

namespace Cntrl;


use App\Model\Projekt;
use Nette\Application\UI\Control;
use Nette\Database\Table\ActiveRow;
use Ublaboo\DataGrid\DataGrid;

class ProjektGridControl extends Control
{
    private $modelProjekt;

    public function __construct(Projekt $modelProjekt)
    {
        $this->modelProjekt = $modelProjekt;
    }

    public function render()
    {
        $this->getTemplate()->setFile(__DIR__ . DIRECTORY_SEPARATOR . "ProjektGridControl.latte");
        $this->getTemplate()->render();
    }

    /**
     * Komponenta pro výpis projektů
     * @param $name
     * @return DataGrid
     */
    public function createComponentVypisProjektu($name)
    {
        $grid = new DataGrid($this, $name);
        $grid->setDataSource($this->modelProjekt->getTable());
        //sloupce
        $grid->addColumnNumber(Projekt::COLUMN_ID, "ID")->setSortable();
        $grid->addColumnText(Projekt::COLUMN_NAZEV_PROJEKTU, "Název projektu")->setSortable();
        $grid->addColumnDateTime(Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU, "Datum odevzdání")->setFormat("d.m.Y")->setSortable();
        $grid->addColumnText(Projekt::COLUMN_TYP_PROJEKTU, "Typ projektu")->setRenderer(function ($item) {
            return Projekt::getTypProjektuNazev($item[Projekt::COLUMN_TYP_PROJEKTU]);
        });
        $grid->addColumnText(Projekt::COLUMN_WEBOVY_PROJEKT, "Webový projekt")->setRenderer(function ($item) {
            return ($item[Projekt::COLUMN_WEBOVY_PROJEKT] ? "Ano" : "Ne");
        });
        //filtry
        $grid->addFilterText(Projekt::COLUMN_ID, "ID");
        $grid->addFilterText(Projekt::COLUMN_NAZEV_PROJEKTU, "Název");
        $grid->addFilterDateRange(Projekt::COLUMN_DATUM_ODEVZDANI_PROJEKTU, "Datum odevzdání");
        $grid->addFilterSelect(Projekt::COLUMN_TYP_PROJEKTU, "Typ projektu", Projekt::getTypyProjektu(true));
        $grid->addFilterSelect(Projekt::COLUMN_WEBOVY_PROJEKT, "Webový projekt", [null => "Vše", 1 => "Ano", 0 => "Ne"]);
        //akce
        $grid->addAction('Homepage:edit', 'Edit')
            ->setIcon('pencil');
        $grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('Smazat')
            ->setClass('btn btn-xs btn-danger')
            ->setConfirm(function ($item) {
                return 'Opravdu odstranit projekt ID ' . $item[Projekt::COLUMN_ID] . ' - %s?';
            }, Projekt::COLUMN_NAZEV_PROJEKTU);

        /**
         * Localization
         */
        $translator = new \Ublaboo\DataGrid\Localization\SimpleTranslator([
            'ublaboo_datagrid.no_item_found_reset' => 'Žádné položky nenalezeny. Filtr můžete vynulovat',
            'ublaboo_datagrid.no_item_found' => 'Žádné položky nenalezeny.',
            'ublaboo_datagrid.here' => 'zde',
            'ublaboo_datagrid.items' => 'Položky',
            'ublaboo_datagrid.all' => 'všechny',
            'ublaboo_datagrid.from' => 'z',
            'ublaboo_datagrid.reset_filter' => 'Resetovat filtr',
            'ublaboo_datagrid.group_actions' => 'Hromadné akce',
            'ublaboo_datagrid.show_all_columns' => 'Zobrazit všechny sloupce',
            'ublaboo_datagrid.hide_column' => 'Skrýt sloupec',
            'ublaboo_datagrid.action' => 'Akce',
            'ublaboo_datagrid.previous' => 'Předchozí',
            'ublaboo_datagrid.next' => 'Další',
            'ublaboo_datagrid.choose' => 'Vyberte',
            'ublaboo_datagrid.execute' => 'Provést',
        ]);
        $grid->setTranslator($translator);

        return $grid;

    }

    public function handleDelete($id)
    {
        $projektRow = $this->modelProjekt->getById($id);
        if (!$projektRow instanceof ActiveRow) {
            $this->getPresenter()->flashMessage("Pokus odstranit neexistujici projket", "danger");
            $this->redirect('this');
        }
        if ($this->modelProjekt->deleteById($id) == 1) {
            $this->getPresenter()->flashMessage("Projekt ID $id byl odstraněn", "success");
            $this->redirect('this');
        }
    }
}

interface IProjektGridFactory
{

    /**
     * @return ProjektGridControl
     */
    public function create();
}