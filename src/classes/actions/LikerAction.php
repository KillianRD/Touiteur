<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\touit\Touit;
use iutnc\touiteur\touit\User;

class LikerAction extends Actions

{
    public function execute(): string
    {
        if (isset($_GET['id'], $_SESSION['user'])) {
            $u = unserialize($_SESSION['user']);

            if ($u instanceof User) {
                Touit::liker($u->id, $_GET['id'], 1);
                return "<p class='msg_liker'>Merci d'avoir donné votre avis</p>" .
                    "<a class='back_nouser' href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";
            } else {
                $html = "<p class='msg_liker'>Vous ne pouvez pas liker ce touit</p>".
                    "<a class='back_nouser' href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";;
                return $html;
            }
        } else {
            $html = "<p class='msg_liker'>Utilisateur non connecté </p>".
                "<a class='back_nouser' href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";;
            return $html;
        }
    }
}