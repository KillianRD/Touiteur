<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\Touit;

class SupprimerTouitAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        if($this->http_method === 'GET'){
            $render = new TouitRender(Touit::getTouit($_GET['id']));
            $html = $render->render(2);
            $html .= <<< END
                <form method='post' action='?action=supprimerTouit&id={$_GET['id']}'>
                <button type='submit'>Êtes vous sûre de supprimer le touit</button>
                </form>
            END;
        } else {
            Touit::supprimerTouit($_GET['id']);
            $html = "Suppression a bien été effectué";
            $html .= "<a href='?action=profil'>Retour sur le Profil</a>";
        }
        return $html;
    }
}