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
        if (isset($_SESSION['user'])) {
            $u = unserialize($_SESSION['user']);
            $listSub = User::render_Sub_Profil($u->id);
            foreach ($listSub as $sub){
                $render = new UserRender($sub);
                $html .= $render->render();
                if(User::CheckUserFollow($sub->id,$u->id)){
                    $html .= "<a href='?action=desabonner&id={$sub->id}'>Abonné</a>";
                } else {
                    $html .= "<a href='?action=suivre&id={$sub->id}'>S'abonner</a>";
                }
            }
            $html .= "<br><a href='?action=profil' >Retour</a>";
        }
        $_SESSION['ancienneQuery'] = 'abonne';
        return $html;
    }
}