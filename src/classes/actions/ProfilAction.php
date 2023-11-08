<?php

namespace iutnc\touiteur\actions;

class ProfilAction extends Actions
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            $html = <<<END
                <a href='?action=profil&sousAction=abonne'>Abonné</a>
                <a href='?action=profil&sousAction=abonnement'>Abonnement</a>
            END;
        } elseif($this->http_method === 'POST') {

            $html = <<<END
                
                
            END;
        }

        return $html;
    }

}