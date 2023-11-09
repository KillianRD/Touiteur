<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\User;

class ProfilAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if (isset($_SESSION['user'])) {
            $html = <<<END
                <a href='?action=abonne'>Abonné</a>
                <a href='?action=abonnement'>Abonnement</a>
                <a href='?action=logout'>Deconnexion</a>
            END;

            $u = unserialize($_SESSION['user']);
            $listTouit = User::render_Profil_Touit($u->id);
            foreach ($listTouit as $touit){
                $render = new TouitRender($touit);
                $html .= $render->render(1);
            }
            $_SESSION['ancienneQuery'] = 'profil';
        } else {
            $html = "<p>Vous n'êtes pas connectez</p>";
            $html .= "<a href='?action=signin'>Merci de vous connectez</a>";
        }
        return $html;
    }

}