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
            $html = "<a href='?action=profil' >Retour</a>";
            $u = unserialize($_SESSION['user']);
            $listSub = User::render_Sub_Profil($u->id);
            foreach ($listSub as $sub){
                $render = new UserRender($sub);
                $html .= $render->render();
                if(User::CheckUserFollow($u->id,$sub->user->id)){
                    $html .= "<a href=?action=desabonner>Abonné</a>";
                } else {
                    $html .= "<a href=?action=sabonner>S'abonner</a>";
                }
            }
        }
        return $html;
    }
}