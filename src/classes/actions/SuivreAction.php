<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\touit\User;

class SuivreAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        if (isset($_GET['id'])) {
            $u = unserialize($_SESSION['user']);
            User::suivreUser($u->id, $_GET['id']);
            return User::renderProfil($_GET['id']);
        }
        return '';
    }
}