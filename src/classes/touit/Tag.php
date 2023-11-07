<?php

namespace iutnc\touiteur\touit;

class Tag
{
    private array $listTouits = [];

    /**
     * @param array $listTouits
     */
    public function __construct(array $listTouits)
    {
        $this->listTouits = $listTouits;
    }

    /**
     * @param Touit $t
     * Ajoute un touit à la liste des touits
     */
    public function ajoutTouit(Touit $t): void
    {
        $this->listTouits[] = $t;
    }

    /**
     * @return array
     * Retourne la liste des touits
     */
    public function getTouits(): array {
        return $this->listTouits;
    }

}