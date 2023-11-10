<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\touit\Touit;

class LikerAction extends Actions

{
    public function execute(): string
    {
        if (isset($_GET['id'])) {
            $u = unserialize($_SESSION['user']);
            Touit::liker($u->id, $_GET['id'], 1);
            return "<p>Merci d'avoir donné votre avis</p>" .
                "<a href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";
        }
        return '';
    }
}