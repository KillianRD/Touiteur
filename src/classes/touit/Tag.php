<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\lists\ListTouit;

class Tag {
    private string $nom;
    private ListTouit $listTouits;

    /**
     * @param array $listTouits
     */
    public function __construct(string $nom) {
        $this->nom = $nom;
        $this->listTouits = new ListTouit();
    }

    /**
     * @param Touit $t
     * Ajoute un touit à la liste des touits
     */
    public function ajoutTouit(Touit $t): void {
       $this->listTouits->add($t);
    }

    /**
     * @param string $at
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }




}