<?php

namespace iutnc\touiteur\admin\actions;



use iutnc\touiteur\admin\auth\Authentification;
use iutnc\touiteur\admin\exceptions\AuthException;

class SigninAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
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
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['mdp'];
            try {
                Authentification::authenticate($email, $password);
                $user = unserialize($_SESSION['user']);
                $html = <<<END
                    <div class="connect_check">
                        <h1>Bienvenue {$user->nom}</h1>
                        <a href='?action=logout'>Se déconnecter</a>
                    </div> 
                END;
            } catch (AuthException $e) {
                $html = <<<END
                    <div class="auth_error">
                        <div class="container_error">
                            <p class="msg_error">Erreur lors de la connexion à votre compte !</p>
                            <img src="./images/colere.png" alt="oiseau colère" class="colere">
                        </div>
                    </div>
                END;
            }
        }
        return $html;
    }
}
