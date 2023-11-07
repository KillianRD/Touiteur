<?php

namespace iutnc\touiteur\actions;

class AbonneAction
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'POST') {
            echo "<button onclick='action=profil' >Retour</button>";
                //a faire
            END;
        }
        return $html;
    }
}