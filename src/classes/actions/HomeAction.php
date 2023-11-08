<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\render\ListTouitRender;

class HomeAction extends Actions
{
    public function execute(): string
    {
        $html = '';
        if(isset($_SESSION['user'])){


        }else{
            $list = ListTouitRender::render_home();
            foreach ($list as $touit) {
                $html .= $touit->render();

            }

        }

        return $html;
    }
}