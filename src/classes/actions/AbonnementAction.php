<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\render\UserRender;
use iutnc\touiteur\touit\User;

class AbonnementAction
{
    public function execute(): string
    {
        $html = '';
        if (isset($_GET['id']) && isset($_SESSION['user'])) {
            $listSub = User::render_Follow_Profil($_GET['id']);
            $html .= "<div class='list_profil_abonne'>\n";
            foreach ($listSub as $sub) {
                $html .= "                       <div class='profil-inlist-abonne'>\n";
                $render = new UserRender($sub);
                $html .= $render->render();
                $html .= "                       </div>\n";
                if (User::CheckUserFollow($sub->id, $_GET['id'])) {
                    $html .= "                     <a href='?action=desabonner&id={$sub->id}' class='profil_desabonner'>Abonné</a><br>";
                } else {
                    $html .= "                     <a href='?action=suivre&id={$sub->id}' class='profil_sabonner'>S'abonner</a><br>";
                }

            }

            $html .= "                     <a href='?action=profil' class='profil_retour' >Retour</a>\n";
            $html .= "                    </div>";
        }
        $_SESSION['ancienneQuery'] = 'profil';
        return $html;
    }
}