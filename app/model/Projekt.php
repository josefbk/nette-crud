<?php

namespace App\Model;


use Nette\Database\Context;
use Nette\Database\Table\ActiveRow;

class Projekt
{
    const TABLE_NAME = "projekt";

    const COLUMN_ID = "id",
        COLUMN_NAZEV_PROJEKTU = "nazev_projektu",
        COLUMN_DATUM_ODEVZDANI_PROJEKTU = "datum_odevzdani_projektu",
        COLUMN_TYP_PROJEKTU = "typ_projektu",
        COLUMN_WEBOVY_PROJEKT = "webovy_projekt";

    const TYP_PROJEKTU_CASOVE_OMEZENY = 1,
        TYP_PROJEKTU_CONTINUOUS_INTEGRATION = 2;

    /**
     * seznam typů projektu
     * @param bool $allowNull
     * @return array
     */
    public static function getTypyProjektu($allowNull = false)
    {
        $arr = [
            self::TYP_PROJEKTU_CASOVE_OMEZENY => "Časově omezený",
            self::TYP_PROJEKTU_CONTINUOUS_INTEGRATION => "Continuous Integration"
        ];
        if ($allowNull) {
            return [null => "Všechny"] + $arr;
        }
        return $arr;
    }

    /**
     * vraci nazev projektu podle id typu
     * @param $typProjektu
     * @return mixed|null
     */
    public static function getTypProjektuNazev($typProjektu)
    {
        $typy = self::getTypyProjektu();
        if (array_key_exists($typProjektu, $typy)) {
            return $typy[$typProjektu];
        }
        return null;
    }

    private $connection;

    public function __construct(Context $context)
    {
        $this->connection = $context;
    }

    public function getTable()
    {
        return $this->connection->table(self::TABLE_NAME);
    }

    /**
     * @param $id
     * @return mixed|\Nette\Database\Table\IRow
     */
    public function getById($id)
    {
        return $this->getTable()->wherePrimary($id)->fetch();
    }

    public function insert($data)
    {
        $this->getTable()->insert($data);
    }

    public function update($data)
    {
        if (!array_key_exists(self::COLUMN_ID, $data)) {
            throw new \Exception("chyby id editovaneho zaznamu");
        }
        $row = $this->getById($data[self::COLUMN_ID]);
        if (!$row instanceof ActiveRow) {
            throw new \Exception("zaznam k editaci nenalezen");
        }
        $row->update($data);
    }

    public function deleteById($id)
    {
        $row = $this->getById($id);
        if ($row instanceof ActiveRow) {
            return $row->delete();
        }
        return 0;
    }
}