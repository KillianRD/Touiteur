<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\auth\Inscription;
use iutnc\touiteur\exceptions\AuthException;

;

class AddUserAction extends Actions
{

    public function execute(): string
    {
        $html = ' ';
        if ($this->http_method === 'GET') {
            $html = <<<END
                <form method='post' action='?action=add-user' class="form_user">
                    <h1 class="h1_user"><img src="/images/oiseau.png" alt="Logo Touiteur" class="logo_touiteur">Inscription</h1>  
                        <div class="container_user">
                            <div class="personne">
                                <input type='text' placeholder="Nom" name='nom' class ="label-input nom "</input> 
                                <input type='text' placeholder="Prenom" name='prenom' class ="label-input prenom" </input>
                            </div>            
                            <input type='text' placeholder="Pseudo" name='pseudo' class ="label-input"</input>
                            <input type='email' placeholder="Email" name='email' class ="label-input"</input>
                            <input type='password' placeholder="Mot de passe" name='password' class ="label-input"'</input>
                            <input type='password' placeholder="Confirmer mot de passe" name='confirm' class ="label-input"</input>
                            <button type='submit' class="button_user">S'inscrire</button>
                        </div> 
                </form>    
END;
        } else {
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_EMAIL);
            $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_EMAIL);
            $pseudo = filter_var($_POST['pseudo'], FILTER_SANITIZE_EMAIL);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];
            $confirm = $_POST['confirm'];

            try {
                Inscription::register($email, $password, $confirm, $nom, $prenom, $pseudo);
                $html = <<<END
                Votre compte a bien été créé.
                END;
            } catch (AuthException $e) {
                $html = <<<END
                <br>Il y a eu un problème lors de la création de votre compte.</br><br>
                <br>Si vous avez déjà un compte vous pouvez vous y connecter en cliquant sur le lien suivant: </br>
                <a href='?action=login'>Connexion</a></br>
                END;
                $html .= "<br><b>" . $e->getMessage() . "</b>";
            }
        }
        return $html;
    }
}