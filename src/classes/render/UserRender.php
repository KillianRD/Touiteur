<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\touit\Touit;
use iutnc\touiteur\touit\User;

class UserRender
{
    /**
     * @var User $touit : Touit à afficher
     */
    private User $user;

    /**
     * @param Touit $t : Touit à afficher
     */
    public function __construct(User $u)
    {
        $this->user = $u;
    }

    /**
     * Methode pour afficher un Profil
     *
     * @return string : Renvoie le touit sous forme de HTML
     */
    public function render(): string
    {
        $html = "<p>@" . "{$this->user->pseudo}</p>";
        return $html;
    }
}