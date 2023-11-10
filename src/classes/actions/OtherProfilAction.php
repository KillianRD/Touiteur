<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\UserInexistantException;
use iutnc\touiteur\touit\User;

class OtherProfilAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        if (isset($_GET['id'])) {
            $html = "<a href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";
            $html .= User::renderProfil($_GET['id']);
        } else {
            if ($this->http_method === 'GET') {
                $html = <<< END
                    <form method='post' action='?action=otherprofil'>
                    <label for="recherche">Chercher une personne : </label>
                    <input type="text" id="recherche" name="recherche" placeholder="Recherche">
                    <input type="submit" value="Rechercher">
                    </form>
                END;
            } else {
                $pseudo = filter_var($_POST['recherche'], FILTER_SANITIZE_STRING);
                try {
                    $id = User::getIdByPseudo($pseudo);
                    $html = User::renderProfil($id);
                    $html .= "<a href='?action={$_SESSION['ancienneQuery']}'>Retour</a>";
                } catch (UserInexistantException $e){
                    $html = "<p>La personne dont vous voulez son profil n'existe pas</p>";
                }
            }
        }
        $_SESSION['ancienneQuery'] = 'home';
        return $html;
    }
}