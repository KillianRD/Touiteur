<?php

namespace iutnc\touiteur\actions;
use iutnc\deefy\action\Actions;
use iutnc\touiteur\auth\Inscription;
use iutnc\touiteur\exceptions\AuthException;

;
class AddUserAction extends Actions
{

    public function execute(): string
    {
        $html = ' ';
        if ($this->http_method === 'GET') {
            return <<<END
                <form method='post' action='?action=add-user'><br><br>
                <label>Nom: </label><input type='text' placeholder='<Nom>' name='nom'<br>
                <label>Prénom: </label><input type='text' placeholder='<Prenom>' name='prenom'<br>
                <label>Pseudo: </label><input type='text' placeholder='<Pseudo>' name='pseudo'<br>
                <label>Email: </label><input type='email' placeholder='<Email>' name='email'<br>
                <label>Mot de passe: </label><input type='password' placeholder='<password>' name='password'<br>
                <label>Saisir à nouveau: </label><input type='password' placeholder='<password>' name='confirm'<br>
                <button type='submit'>s'inscrire</button><br><br>
                </form>
                END;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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