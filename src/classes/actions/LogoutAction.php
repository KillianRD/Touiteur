<?php

namespace iutnc\touiteur\actions;


use iutnc\deefy\action\Actions;

class LogoutAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            $html = <<<END
                <form method='post' action='?action=logout'>
                <button type='submit'>Se deconnecter</button><br><br>
                </form>
            END;
        } else {
            //supp cookies de session
            unset($_SESSION['user']);
            $html = <<<END
                <h1>Bienvenue sur Touiteur</h1>
                <a href='?action=lignin'>Se connecter</a>
            END;
        }

        return $html;
    }
}



