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
                <form method='post' action='?action=tag'>
                <label for="recherche">Chercher un tag : </label>
                <input type="text" id="recherche" name="recherche" placeholder="Recherche">
                <input type="submit" value="Rechercher">
                </form>
            END;
        } else {
            $recherche = filter_var($_POST['recherche'], FILTER_SANITIZE_STRING);

            $html = "<h1>Les touits avec le tag {$recherche}</h1>";
            $list = Tag::getTouitbyTag($recherche);
            foreach ($list as $touit){
                $render = new TouitRender($touit);
                $html .= $render->render();
            }
        }
        return $html;
    }
}