<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\touit\User;

class ProfilAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        if (isset($_SESSION['user'])) {
            $u = unserialize($_SESSION['user']);
            $html = User::renderProfil($u->id);
            $html .= "<a href='?action=logout'>Deconnexion</a>";
            $_SESSION['ancienneQuery'] = 'profil';
        } else {
            $html = "<p>Vous n'êtes pas connectez</p>";
            $html .= "<a href='?action=signin'>Merci de vous connectez</a>";
        }
        return $html;
    }

}