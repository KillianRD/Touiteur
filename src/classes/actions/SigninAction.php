<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Authentification;
use iutnc\touiteur\exceptions\AuthException;

class SigninAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            $html = <<<END
                <form method='post' action='?action=signin'>
                <h1>Bienvenue sur Touiteur</h1>
                <label>Email : </label><input type='text' name='email'>
                <label>Mot de passe : </label><input type='password' name='mdp'>
                <button type='submit'>Se connecter</button><br><br>
                Vous vous êtes jamais inscrit <a href='?action=add-user'>Inscrivez vous dès maintenant</a>
                </form>
            END;
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['mdp'];
            try {
                Authentification::authenticate($email, $password);
                $user = unserialize($_SESSION['user']);
                var_dump($_SESSION['user']);
                $html = <<<END
                    <h1>Bienvenue {$user->pseudo}</h1>
                    <a href='?action=logout'>Se déconnecter</a>
                END;
            } catch (AuthException $e) {
                $html = <<<END
                <br>Il y a eu un problème lors de la connexion à votre compte.</br><br>
                <br><b> Il se pourrait que vous n'avez pas de compte, si vous le souhaitez vous pouvez en créer un en cliquant sur le lien suivant: </b> 
                <br><a href='?action=add-user'>Inscription</a></br>
                END;
            }
        }
        return $html;
    }
}
