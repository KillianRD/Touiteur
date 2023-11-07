<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\lists\ListTouit;

class Tag
{
    private ListTouit $listTouits;

    /**
     * @param array $listTouits
     */
    public function __construct(ListTouit $listTouits)
    {
        $this->listTouits = $listTouits;
    }

    /**
     * @param Touit $t
     * Ajoute un touit à la liste des touits
     */
    public function ajoutTouit(Touit $t): void {
       $this->listTouits->add($t);
    }

    /**
     * @return array
     * Retourne la liste des touits
     */
    public function getTouits(): array {
        return $this->listTouits;
    }

}