<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\TagInexistantException;
use iutnc\touiteur\touit\Tag;

class SuivreTagAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === "GET") {
            $html = <<< END
                <form method='post' action='?action=SuivreTag'>
                <label for="recherche">S'abonner à un tag : </label>
                <input type="text" id="recherche" name="recherche" placeholder="Recherche">
                <input type="submit" value="Rechercher">
                </form>
            END;
        } else {
            $recherche = filter_var($_POST['recherche'], FILTER_SANITIZE_STRING);
            try {
                $u = unserialize($_SESSION['user']);
                Tag::AbonnementTag($recherche, $u->id);
            } catch (TagInexistantException $e) {
                $html .= "Le tag que vous voulez accèder n'existe pas";
            }
        }
        return $html;
    }
}