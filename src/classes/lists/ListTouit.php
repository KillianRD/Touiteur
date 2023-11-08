<?php

namespace iutnc\touiteur\lists;

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\TouitInexistantException;
use iutnc\touiteur\touit\Touit;

class ListTouit {
    /**
     * @var int $nbTouits : Nombre de touits
     */
    private int $nbTouits =0;
    /**
     *
     * @var array $touits : Liste des touits
     */
    private array $touits = [];

    /**
     * @param array $touits : Liste des touits
     * @throws InvalidArgumentException : Si la liste ne contient pas que des touits
     */
    public function __construct(array $touits =[]) {
        if(!empty($touits)) {
            foreach ($touits as $touit) {
                if(!$touit instanceof Touit) {
                    throw new InvalidArgumentException("La liste ne doit contenir que des touits");
                }
            }
            $this->touits = $touits;
            $this->nbTouits = count($touits);
        }
    }

    /**
     * Methode qui permet d'ajouter un touit à la liste des touits
     *
     * @param Touit $t : Touit à ajouter
     * @return void
     */
    public function add(Touit $t){
        array_push($this->touits, $t);
        $this->nbTouits++;
    }

    /**
     * Methode qui permet de supprimer un touit de la liste des touits
     *
     * @param Touit $t : Touit à supprimer
     * @return void
     * @throws TouitInexistantException : Si le touit n'existe pas
     */
    public function suppr(Touit $t) {
        $index = array_search($t, $this->touits);
        if ($index !== false) {
            unset($this->touits[$index]);
        } else {
            throw new TouitInexistantException("Le touit n'existe pas");
        }
    }

    /**
     * Methode pour récupérer les propriétés de la classe
     *
     * @param string $at : Nom de la propriété
     * @return mixed : Valeur de la propriété
     * @throws InvalidPropertyNameException : Si la propriété n'existe pas
     */
    public function __get(string $at):mixed {
        if (property_exists($this,$at)) return $this->$at;
        throw new InvalidPropertyNameException(get_called_class()." attribut invalid". $at);
    }
}