<?php

namespace iutnc\touiteur\lists;

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\TouitInexistantException;
use iutnc\touiteur\touit\Touit;

class ListTouit {
    private int $nbTouits =0;
    private array $touits = [];

    /**
     * @param int $nbTouits
     * @param array $touits
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

    public function add(Touit $t){
        array_push($this->touits, $t);
        $this->nbTouits++;
    }

    public function suppr(Touit $t) {
        $index = array_search($t, $this->touits);
        if ($index !== false) {
            unset($this->touits[$index]);
        } else {
            throw new TouitInexistantException("Le touit n'existe pas");
        }
    }

    public function __get(string $at):mixed {
        if (property_exists($this,$at)) return $this->$at;
        throw new InvalidPropertyNameException(get_called_class()." attribut invalid". $at);
    }
}