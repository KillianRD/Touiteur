<?php

namespace iutnc\touiteur\admin\actions;

use iutnc\touiteur\admin\touit\User;
class InfluenceurAction extends Actions
{

    public function execute(): string
    {
        return User::listInfluenceur();
    }
}