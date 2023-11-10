<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\UserRender;
use iutnc\touiteur\touit\User;

class AbonneAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if(isset($_GET['id'])){
            $listSub = User::render_Sub_Profil($_GET['id']);
            $html .= "<div class='list_profil_abonne'>\n";
            foreach ($listSub as $sub){
                $html .= "                       <div class='profil-inlist-abonne'>\n";
                $render = new UserRender($sub);
                $html .= $render->render();
                $html .= "                       </div>\n";
                if(User::CheckUserFollow($sub->id, $_GET['id'])){
                    $html .= "                     <a href='?action=desabonner&id={$sub->id}' class='profil_desabonner'>Abonné</a>\n";
                } else {
                    $html .= "                     <a href='?action=suivre&id={$sub->id}' class='profil_sabonner'>S'abonner</a>\n";
                }
            }

            $html .= "                     <a href='?action=profil' class='profil_retour' >Retour</a>\n";
            $html .= "                    </div>";
        }
        $_SESSION['ancienneQuery'] = 'abonne';
        return $html;
    }
}