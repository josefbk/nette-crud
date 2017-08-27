<?php

namespace App\Grids;


use Ublaboo\DataGrid\DataGrid;

class UblabooFactory
{

    public function create($that, $name)
    {
        $grid = new DataGrid($that, $name);

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
            'ublaboo_datagrid.daterange_alternative_do' => 'Do', //+ rozsireni puvodniho ublaboo o vlastni daterange latte
            'ublaboo_datagrid.daterange_alternative_od' => 'Od', //+ rozsireni puvodniho ublaboo o vlastni daterange latte
        ]);
        $grid->setTranslator($translator);

        return $grid;
    }

    public function getFilterDateRangeAlternativeTemplate()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "datagrid_filter_daterange_alternative.latte";
    }
}