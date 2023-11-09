<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\touit\User;

class DesabonnerAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        if(isset($_GET['id'])){
            $u = unserialize($_SESSION['user']);
            User::nePlusSuivreUser($u->id, $_GET['id']);
            return User::renderProfil($_GET['id']);
        }
        return '';
    }
}