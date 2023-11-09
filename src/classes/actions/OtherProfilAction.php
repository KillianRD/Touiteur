<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\User;

class OtherProfilAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if (isset($_GET['id'])) {
            $listTouit = User::render_Profil_Touit($_GET['id']);
            foreach ($listTouit as $touit) {
                $render = new TouitRender($touit);
                $html .= $render->render(1);
            }
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

            }
        }
        $_SESSION['ancienneQuery'] = 'otherprofil';
        return $html;
    }
}