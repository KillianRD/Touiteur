<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\touit\Touit;
use iutnc\touiteur\touit\User;

class UserRender
{
    /**
     * @var User $touit : User à afficher
     */
    private User $user;

    /**
     * @param User $t : User à afficher
     */
    public function __construct(User $u)
    {
        $this->user = $u;
    }

    /**
     * Methode pour afficher un User
     *
     * @return string : Renvoie le User sous forme de HTML
     */
    public function render(): string
    {
        return "<p>@" . "{$this->user->pseudo}</p>" .
            "<p>{$this->user->nom}</p>" .
            "<p>{$this->user->prenom}</p>";
    }
}