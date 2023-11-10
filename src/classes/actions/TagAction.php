<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\Tag;

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
                    <label for="recherche">Chercher un tag : </label>
                    <input type="text" name="recherche_tag" placeholder="Recherche">
                    <input type="submit" value="Rechercher">
                </form>
            END;
        } else {
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
        }
        return $html;
    }
}