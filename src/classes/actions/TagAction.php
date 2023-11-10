<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\Tag;
use iutnc\touiteur\touit\User;

class TagAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';

        if ($this->http_method === 'GET') {
            $html = <<<END
                <form method='post' action='?action=tag' class="form_tag">
                    <input type="hidden" name="form_type" value="tag">
                    <label for="recherche">Chercher un tag : </label>
                    <input type="text" name="recherche_tag" placeholder="Recherche">
                    <input type="submit" value="Rechercher">
                </form>
                <form method='post' action='?action=tag' class="form_cherch_profil">
                    <input type="hidden" name="form_type" value="profil">
                    <label for="recherche">Chercher un profil : </label>
                    <input type="text" name="recherche_profil" placeholder="Recherche">
                    <input type="submit" value="Rechercher">
                </form>
            END;
        } else {
            $form_type = filter_var($_POST['form_type'], FILTER_SANITIZE_STRING);

            if ($form_type === 'tag') {
                $recherche = filter_var($_POST['recherche_tag'], FILTER_SANITIZE_STRING);
                $html = "<h1 class='h1_tag'>Les Touits avec le tag {$recherche}</h1>\n";
                $list = Tag::getTouitbyTag($recherche);
                $html .= "<div class='list_touits_tag'>\n";
                foreach ($list as $touit){
                    $render = new TouitRender($touit);
                    $html .= $render->render(1);
                }
                $html .= "</div>";
                $_SESSION['ancienneQuery'] = "tag";
            } elseif ($form_type === 'profil') {
                $rechercherp = filter_var($_POST['recherche_profil'], FILTER_SANITIZE_STRING);

                try {
                    $id = User::getIdByPseudo($rechercherp);
                    $html .= User::renderProfil($id);
                } catch (\Exception $e) {
                    $html .= "<p>L'utilisateur n'a pas été trouvé.</p>";
                }
            }
            $_SESSION['ancienneQuery'] = "tag";
        }

        return $html;
    }
}
