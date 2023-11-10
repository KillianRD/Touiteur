<?php

namespace iutnc\touiteur\actions;

class LogoutAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            $html = <<<END
                <form method='post' action='?action=logout'>
                <button type='submit'>Se deconnecter</button><br><br>
                </form>
            END;
        } else {
            //supp cookies de session
            unset($_SESSION['user']);
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

        return $html;
    }
}



