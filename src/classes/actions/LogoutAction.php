<?php

namespace iutnc\touiteur\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\render\AudioListRenderer;

class LogoutAction extends Actions
{

    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            return <<<END
                <form method='post' action='?action=signin'>
                <button type='submit'>Se deconnecter</button><br><br>
                </form>
            END;
        } elseif($this->http_method === 'POST') {
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



