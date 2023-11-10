<?php

namespace iutnc\touiteur\admin\actions;


use iutnc\touiteur\admin\exceptions\InvalideTouitException;

class HomeAction extends Actions
{
    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if (isset($_SESSION['user'])) {

        } else {

        }
        return $html;
    }
}