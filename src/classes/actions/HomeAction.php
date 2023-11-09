<?php

namespace iutnc\touiteur\actions;

use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\ListTouitRender;
use iutnc\touiteur\render\TouitRender;

class HomeAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if (isset($_SESSION['user'])) {
            $u = unserialize($_SESSION['user']);
            $list = ListTouitRender::render_sub($u->id);
            foreach ($list as $touit) {
                $render = new TouitRender($touit);
                $html .= $render->render(1);
            }
        } else {
            $list = ListTouitRender::render_home();
            foreach ($list as $touit) {
                $render = new TouitRender($touit);
                $html .= $render->render(1);
            }
        }
        $_SESSION['ancienneQuery'] = 'home';
        return $html;
    }
}