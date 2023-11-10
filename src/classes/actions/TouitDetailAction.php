<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\Touit;

class TouitDetailAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $t = Touit::getTouit($_GET['id']);
        $render = new TouitRender($t);
        $html = $render->render(2);
        $html .= "<a href='?action={$_SESSION['ancienneQuery']}' class='back'>Retour</a>";
        $_SESSION['ancienneQuery'] = "TouitDetail&id={$_GET['id']}";
        return $html;
    }
}