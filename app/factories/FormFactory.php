<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nextras\Forms\Rendering\Bs3FormRenderer;

class FormFactory
{
    use Nette\SmartObject;

    public function create()
    {
        $form = new Form;
        $form->setRenderer(new Bs3FormRenderer());
        return $form;
    }
}