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
        } else {
            $html = <<<END
        <form method='post' action='?action=signin' class="form_signin"> 
                    <h1 class="h1_signin"><img src="./images/oiseau.png" alt="Logo Touiteur" class="oiseau">Bienvenue sur Touiteur</h1>
                    <div class="container_signin">
                        <input type='text' placeholder="Email" name='email'>
                        <input type='text' placeholder="Mot de passe" name='mdp'>
                        <button type='submit' class="button_signin">Se connecter</button>
                        <p class="separation">______________________________________________</p>
                        <a href='?action=add-user' class="inscription">Créer un compte</a>
                    </div>
        </form> 
END;
        }
        $_SESSION['ancienneQuery'] = 'profil';
        return $html;
    }

}