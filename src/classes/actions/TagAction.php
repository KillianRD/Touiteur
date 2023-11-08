<?php

namespace iutnc\touiteur\actions;

class TagAction extends Actions
{
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

            $html = <<<END
                <h1>Les touits avec le tag {$recherche}</h1>
                //a faire
            END;
        }

        return $html;
    }
}